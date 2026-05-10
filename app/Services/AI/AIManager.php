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

    public function chat(array $payload, bool $stream = false)
    {
        $providers = $this->providerManager->getActiveProviders();

        foreach ($providers as $provider) {
            try {
                return $this->processWithProvider($provider, $payload, $stream);
            } catch (Exception $e) {
                Log::warning("Provider [{$provider->name}] failed: " . $e->getMessage());
                continue; // Fallback to next provider
            }
        }

        throw new Exception("All AI providers failed or no active providers available.");
    }

    protected function processWithProvider(AiProvider $provider, array $payload, bool $stream)
    {
        while ($apiKey = $this->keyRotator->getAvailableKey($provider)) {
            try {
                $startTime = microtime(true);
                $driver = $this->providerManager->getDriver($provider, $apiKey);
                $response = $driver->chat($payload, $stream);
                $responseTime = microtime(true) - $startTime;

                if ($response->successful()) {
                    $this->keyRotator->markAsUsed($apiKey);
                    
                    // Dispatch log job
                    \App\Jobs\ProcessAiLog::dispatch([
                        'provider_id' => $provider->id,
                        'api_key_id' => $apiKey->id,
                        'model' => $payload['model'] ?? 'default',
                        'response_time' => $responseTime,
                        'status' => 'success',
                    ]);

                    return $response;
                }

                if ($response->status() === 429) { // Rate Limit
                    $this->keyRotator->setCooldown($apiKey);
                    
                    \App\Jobs\ProcessAiLog::dispatch([
                        'provider_id' => $provider->id,
                        'api_key_id' => $apiKey->id,
                        'model' => $payload['model'] ?? 'default',
                        'response_time' => $responseTime,
                        'status' => 'rate_limit',
                    ]);
                    
                    continue; // Try next key
                }

                $this->keyRotator->markAsError($apiKey);
                
                \App\Jobs\ProcessAiLog::dispatch([
                    'provider_id' => $provider->id,
                    'api_key_id' => $apiKey->id,
                    'model' => $payload['model'] ?? 'default',
                    'response_time' => $responseTime,
                    'status' => 'error',
                    'error_message' => $response->body(),
                ]);
                
                throw new Exception("API error: " . $response->body());

            } catch (Exception $e) {
                $this->keyRotator->markAsError($apiKey);
                Log::error("Key error for [{$provider->name}]: " . $e->getMessage());
                
                \App\Jobs\ProcessAiLog::dispatch([
                    'provider_id' => $provider->id,
                    'api_key_id' => $apiKey->id,
                    'model' => $payload['model'] ?? 'default',
                    'status' => 'exception',
                    'error_message' => $e->getMessage(),
                ]);

                if (str_contains($e->getMessage(), 'rate limit')) {
                    $this->keyRotator->setCooldown($apiKey);
                    continue;
                }
                
                throw $e;
            }
        }

        throw new Exception("No available API keys for provider [{$provider->name}].");
    }
}
