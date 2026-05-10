<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use Exception;
use Illuminate\Support\Facades\Log;

class AIManager
{
    protected ProviderManager $providerManager;
    protected KeyRotator $keyRotator;

    public function __construct(ProviderManager $providerManager, KeyRotator $keyRotator)
    {
        $this->providerManager = $providerManager;
        $this->keyRotator = $keyRotator;
    }

    public function chat(array $payload, bool $stream = false, ?int $providerId = null)
    {
        $providers = $this->providerManager->getActiveProviders();

        if ($providerId) {
            $requestedProvider = $providers->firstWhere('id', $providerId);
            if ($requestedProvider) {
                $providers = $providers->reject(fn($p) => $p->id == $providerId)->prepend($requestedProvider);
            }
        }

        foreach ($providers as $provider) {
            try {
                // If a specific provider was requested via $providerId, and we are in the playground loop,
                // we only want to try THIS provider. 
                // But the loop here is the global fallback. 
                // In Playground, we call chat(..., $p->id). 
                // So here $providers will basically just be that one provider at the top.
                
                return $this->processWithProvider($provider, $payload, $stream, $providerId !== null);
            } catch (Exception $e) {
                Log::warning("Provider [{$provider->name}] failed: " . $e->getMessage());
                if ($providerId) {
                    throw $e;
                }
                continue; 
            }
        }

        throw new Exception("All AI providers failed or no active providers available.");
    }

    protected function processWithProvider(AiProvider $provider, array $payload, bool $stream, bool $isSpecificProvider = false)
    {
        $requestedModel = $payload['model'] ?? null;
        
        // Get models to try
        if ($requestedModel) {
            $models = [$requestedModel];
        } else {
            $models = $provider->models()->where('is_active', true)->pluck('model_name')->toArray();
        }

        if (empty($models)) {
            throw new Exception("No active models found for provider [{$provider->name}].");
        }

        foreach ($models as $modelName) {
            $currentPayload = $payload;
            $currentPayload['model'] = $modelName;

            // Reset keys for each model attempt to ensure we try all keys for each model
            // Actually, we should try all keys for the provider.
            
            while ($apiKey = $this->keyRotator->getAvailableKey($provider)) {
                try {
                    $startTime = microtime(true);
                    $driver = $this->providerManager->getDriver($provider, $apiKey);
                    $response = $driver->chat($currentPayload, $stream);
                    $responseTime = microtime(true) - $startTime;

                    if ($response->successful()) {
                        $this->keyRotator->markAsUsed($apiKey);
                        
                        \App\Jobs\ProcessAiLog::dispatch([
                            'provider_id' => $provider->id,
                            'api_key_id' => $apiKey->id,
                            'model' => $modelName,
                            'response_time' => $responseTime,
                            'status' => 'success',
                        ]);

                        return [
                            'response' => $response,
                            'provider' => $provider,
                            'model' => $modelName,
                        ];
                    }

                    // If it's a model-specific error (like 404), try the next model
                    if ($response->status() === 404 || str_contains($response->body(), 'not found') || str_contains($response->body(), 'model')) {
                        Log::info("Model [{$modelName}] not found for [{$provider->name}]. Trying next model...");
                        break; // Break the while loop to try next model
                    }

                    if ($response->status() === 429) { // Rate Limit
                        $this->keyRotator->setCooldown($apiKey);
                        continue; // Try next key
                    }

                    $this->keyRotator->markAsError($apiKey);
                    throw new Exception("API error ({$response->status()}): " . $response->body());

                } catch (Exception $e) {
                    $this->keyRotator->markAsError($apiKey);
                    Log::error("Key error for [{$provider->name}] with model [{$modelName}]: " . $e->getMessage());
                    
                    // If it's just a key error, try next key. If it's a model error, we already broke.
                    continue; 
                }
            }
        }

        throw new Exception("All models and keys failed for provider [{$provider->name}].");
    }
}
