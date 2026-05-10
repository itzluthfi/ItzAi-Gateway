<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiApiKey extends Model
{
    protected $fillable = [
        'provider_id',
        'api_key',
        'status',
        'priority',
        'usage_count',
        'error_count',
        'cooldown_until',
        'last_used_at',
    ];

    protected $casts = [
        'api_key' => 'encrypted',
        'cooldown_until' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'provider_id');
    }
}
