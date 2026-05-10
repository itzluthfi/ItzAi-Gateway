<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiModel extends Model
{
    protected $fillable = [
        'provider_id',
        'model_name',
        'is_active',
        'is_free',
        'context_length',
    ];

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'provider_id');
    }
}
