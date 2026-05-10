<?php

namespace App\Services\AI\Drivers;

class GeminiDriver extends BaseDriver
{
    public function getName(): string
    {
        return 'gemini';
    }

    protected function getDefaultBaseUrl(): string
    {
        return 'https://generativelanguage.googleapis.com/v1beta/models/';
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function chat(array $payload, bool $stream = false)
    {
        $model = $payload['model'] ?? 'gemini-1.5-flash';
        $apiKey = $this->apiKey->api_key;
        
        $url = "{$this->baseUrl}{$model}:" . ($stream ? 'streamGenerateContent' : 'generateContent') . "?key={$apiKey}";

        // Format payload to Gemini format if not already
        $geminiPayload = $this->formatPayload($payload);

        if ($stream) {
            return $this->getHttpClient()->withOptions(['stream' => true])->post($url, $geminiPayload);
        }

        return $this->getHttpClient()->post($url, $geminiPayload);
    }

    protected function formatPayload(array $payload): array
    {
        // Simple conversion for now, can be improved
        return [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $payload['message'] ?? '']]
                ]
            ],
            'generationConfig' => [
                'temperature' => $payload['temperature'] ?? 0.7,
                'maxOutputTokens' => $payload['max_tokens'] ?? 2048,
            ]
        ];
    }
}
