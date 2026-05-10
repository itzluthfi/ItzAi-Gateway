<?php

namespace App\Jobs;

use App\Models\AiApiKey;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Carbon\Carbon;

class CleanupCooldownKeys implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        AiApiKey::where('status', 'limited')
            ->where('cooldown_until', '<=', Carbon::now())
            ->update([
                'status' => 'active',
                'cooldown_until' => null,
            ]);
    }
}
