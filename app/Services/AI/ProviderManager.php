<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use App\Services\AI\Drivers\GeminiDriver;
use App\Services\AI\Drivers\GroqDriver;
use App\Services\AI\Drivers\OpenRouterDriver;
use App\Models\AiApiKey;
use Exception;

class ProviderManager
{
    public function getDriver(AiProvider $provider, AiApiKey $apiKey)
    {
        return match ($provider->slug) {
            'gemini' => new GeminiDriver($apiKey, $provider->base_url),
            'groq' => new GroqDriver($apiKey, $provider->base_url),
            'openrouter' => new OpenRouterDriver($apiKey, $provider->base_url),
            default => throw new Exception("Driver for provider [{$provider->slug}] not found."),
        };
    }

    public function getActiveProviders()
    {
        return AiProvider::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get();
    }
}
