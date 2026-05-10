<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'provider_id',
        'api_key_id',
        'model',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'response_time',
        'status',
        'error_message',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'provider_id');
    }

    public function apiKey()
    {
        return $this->belongsTo(AiApiKey::class, 'api_key_id');
    }
}
