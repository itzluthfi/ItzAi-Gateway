<?php

namespace App\Repositories;

use App\Models\AiApiKey;
use Carbon\Carbon;

class ApiKeyRepository
{
    public function getActiveKeysByProvider(int $providerId)
    {
        return AiApiKey::where('provider_id', $providerId)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('cooldown_until')
                    ->orWhere('cooldown_until', '<=', Carbon::now());
            })
            ->get();
    }

    public function updateCooldown(int $keyId, Carbon $until)
    {
        return AiApiKey::where('id', $keyId)->update([
            'status' => 'limited',
            'cooldown_until' => $until,
        ]);
    }
}
