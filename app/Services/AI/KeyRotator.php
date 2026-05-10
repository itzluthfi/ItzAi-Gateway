<?php

namespace App\Services\AI;

use App\Models\AiApiKey;
use App\Models\AiProvider;
use Carbon\Carbon;

class KeyRotator
{
    public function getAvailableKey(AiProvider $provider)
    {
        return AiApiKey::where('provider_id', $provider->id)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('cooldown_until')
                    ->orWhere('cooldown_until', '<=', Carbon::now());
            })
            ->orderBy('priority', 'desc')
            ->orderBy('last_used_at', 'asc')
            ->first();
    }

    public function setCooldown(AiApiKey $apiKey, int $minutes = 60)
    {
        $apiKey->update([
            'status' => 'limited',
            'cooldown_until' => Carbon::now()->addMinutes($minutes),
        ]);
    }

    public function markAsUsed(AiApiKey $apiKey)
    {
        $apiKey->increment('usage_count');
        $apiKey->update(['last_used_at' => Carbon::now()]);
    }

    public function markAsError(AiApiKey $apiKey)
    {
        $apiKey->increment('error_count');
    }
}
