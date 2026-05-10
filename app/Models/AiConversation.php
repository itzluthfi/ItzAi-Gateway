<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiConversation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'session_id',
        'role',
        'message',
        'provider_id',
        'model',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'provider_id');
    }
}
