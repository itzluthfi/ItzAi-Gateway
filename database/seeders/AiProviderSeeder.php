<?php

namespace Database\Seeders;

use App\Models\AiProvider;
use Illuminate\Database\Seeder;

class AiProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            [
                'name' => 'Google Gemini',
                'slug' => 'gemini',
                'base_url' => 'https://generativelanguage.googleapis.com/v1/',
                'is_active' => true,
                'priority' => 10,
            ],
            [
                'name' => 'Groq Cloud',
                'slug' => 'groq',
                'base_url' => 'https://api.groq.com/openai/v1/',
                'is_active' => true,
                'priority' => 20,
            ],
            [
                'name' => 'OpenRouter',
                'slug' => 'openrouter',
                'base_url' => 'https://openrouter.ai/api/v1/',
                'is_active' => true,
                'priority' => 5,
            ],
            [
                'name' => 'DeepSeek',
                'slug' => 'deepseek',
                'base_url' => 'https://api.deepseek.com/',
                'is_active' => true,
                'priority' => 15,
            ],
            [
                'name' => 'Anthropic',
                'slug' => 'anthropic',
                'base_url' => 'https://api.anthropic.com/v1/',
                'is_active' => true,
                'priority' => 8,
            ],
            [
                'name' => 'Mistral AI',
                'slug' => 'mistral',
                'base_url' => 'https://api.mistral.ai/v1/',
                'is_active' => true,
                'priority' => 7,
            ],
        ];

        foreach ($providers as $provider) {
            AiProvider::updateOrCreate(['slug' => $provider['slug']], $provider);
        }
    }
}
