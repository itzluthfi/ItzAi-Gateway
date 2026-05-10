<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AI\AIManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    protected AIManager $aiManager;

    public function __construct(AIManager $aiManager)
    {
        $this->aiManager = $aiManager;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'model' => 'nullable|string',
            'stream' => 'nullable|boolean',
        ]);

        $payload = $request->only(['message', 'model', 'temperature', 'max_tokens']);
        $stream = $request->boolean('stream', false);

        if ($stream) {
            return $this->handleStreaming($payload);
        }

        try {
            // Log user message
            \App\Models\AiConversation::create([
                'user_id' => $request->user()?->id,
                'session_id' => $request->session()->getId(),
                'role' => 'user',
                'message' => $payload['message'],
                'created_at' => now(),
            ]);

            $response = $this->aiManager->chat($payload);
            $responseData = $response->json();

            // Log AI response
            \App\Models\AiConversation::create([
                'user_id' => $request->user()?->id,
                'session_id' => $request->session()->getId(),
                'role' => 'assistant',
                'message' => $responseData['choices'][0]['message']['content'] ?? json_encode($responseData),
                'provider_id' => null, // Can be updated if AIManager returns provider info
                'model' => $payload['model'] ?? 'default',
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'provider' => $responseData['provider'] ?? 'unknown',
                'model' => $responseData['model'] ?? $payload['model'],
                'response' => $responseData['choices'][0]['message']['content'] ?? $responseData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    protected function handleStreaming(array $payload)
    {
        return new StreamedResponse(function () use ($payload) {
            try {
                $response = $this->aiManager->chat($payload, true);
                
                // Note: Actual streaming parsing depends on the provider's response format.
                // This is a simplified version.
                foreach ($response->toPsrResponse()->getBody() as $chunk) {
                    echo "data: " . $chunk . "\n\n";
                    ob_flush();
                    flush();
                }
            } catch (\Exception $e) {
                echo "event: error\ndata: " . json_encode(['error' => $e->getMessage()]) . "\n\n";
                ob_flush();
                flush();
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
