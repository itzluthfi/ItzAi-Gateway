<?php

namespace App\Services\AI\Drivers;

class OpenRouterDriver extends BaseDriver
{
    public function getName(): string
    {
        return 'openrouter';
    }

    protected function getDefaultBaseUrl(): string
    {
        return 'https://openrouter.ai/api/v1/chat/completions';
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey->api_key,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ];
    }

    public function chat(array $payload, bool $stream = false)
    {
        $payload['stream'] = $stream;

        if ($stream) {
            return $this->getHttpClient()->withOptions(['stream' => true])->post($this->baseUrl, $payload);
        }

        return $this->getHttpClient()->post($this->baseUrl, $payload);
    }
}
