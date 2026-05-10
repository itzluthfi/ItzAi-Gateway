<?php

namespace App\Services\AI\Drivers;

class GroqDriver extends BaseDriver
{
    public function getName(): string
    {
        return 'groq';
    }

    protected function getDefaultBaseUrl(): string
    {
        return 'https://api.groq.com/openai/v1/chat/completions';
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey->api_key,
            'Content-Type' => 'application/json',
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
