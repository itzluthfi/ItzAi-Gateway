<?php

namespace App\Jobs;

use App\Models\AiLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessAiLog implements ShouldQueue
{
    use Queueable;

    protected array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        AiLog::create([
            'provider_id' => $this->data['provider_id'] ?? null,
            'api_key_id' => $this->data['api_key_id'] ?? null,
            'model' => $this->data['model'] ?? null,
            'prompt_tokens' => $this->data['prompt_tokens'] ?? 0,
            'completion_tokens' => $this->data['completion_tokens'] ?? 0,
            'total_tokens' => $this->data['total_tokens'] ?? 0,
            'response_time' => $this->data['response_time'] ?? 0,
            'status' => $this->data['status'] ?? 'success',
            'error_message' => $this->data['error_message'] ?? null,
            'created_at' => now(),
        ]);
    }
}
