<?php

namespace App\Repositories;

use App\Models\AiProvider;

class ProviderRepository
{
    public function getAllActive()
    {
        return AiProvider::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get();
    }

    public function findBySlug(string $slug)
    {
        return AiProvider::where('slug', $slug)->first();
    }
}
