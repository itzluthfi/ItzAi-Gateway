<?php

namespace App\Services\AI\Drivers;

use App\Services\AI\Contracts\AIProviderInterface;
use App\Models\AiApiKey;
use Illuminate\Support\Facades\Http;

abstract class BaseDriver implements AIProviderInterface
{
    protected AiApiKey $apiKey;
    protected string $baseUrl;

    public function __construct(AiApiKey $apiKey, ?string $baseUrl = null)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl ?? $this->getDefaultBaseUrl();
    }

    abstract protected function getDefaultBaseUrl(): string;

    protected function getHttpClient()
    {
        return Http::withHeaders($this->getHeaders());
    }

    abstract protected function getHeaders(): array;
}
