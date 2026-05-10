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
        return 'https://generativelanguage.googleapis.com/v1/models/';
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
        
        $baseUrl = str_ends_with($this->baseUrl, '/') ? $this->baseUrl : "{$this->baseUrl}/";
        if (! str_contains($baseUrl, 'models/')) {
            $baseUrl .= 'models/';
        }
        
        $url = "{$baseUrl}{$model}:" . ($stream ? 'streamGenerateContent' : 'generateContent') . "?key={$apiKey}";

        // Format payload to Gemini format if not already
        $geminiPayload = $this->formatPayload($payload);

        if ($stream) {
            return $this->getHttpClient()->withOptions(['stream' => true])->post($url, $geminiPayload);
        }

        return $this->getHttpClient()->post($url, $geminiPayload);
    }

    protected function formatPayload(array $payload): array
    {
        $message = $payload['message'] ?? '';
        
        // If 'messages' array is provided, take the last user message
        if (isset($payload['messages']) && is_array($payload['messages'])) {
            $lastMessage = collect($payload['messages'])->where('role', 'user')->last();
            $message = $lastMessage['content'] ?? $message;
        }

        return [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $message]]
                ]
            ],
            'generationConfig' => [
                'temperature' => $payload['temperature'] ?? 0.7,
                'maxOutputTokens' => $payload['max_tokens'] ?? 2048,
            ]
        ];
    }
}
