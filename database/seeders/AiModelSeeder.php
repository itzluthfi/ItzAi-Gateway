<?php

namespace Database\Seeders;

use App\Models\AiModel;
use App\Models\AiProvider;
use Illuminate\Database\Seeder;

class AiModelSeeder extends Seeder
{
    public function run(): void
    {
        $gemini = AiProvider::where('slug', 'gemini')->first();
        $groq = AiProvider::where('slug', 'groq')->first();
        $openrouter = AiProvider::where('slug', 'openrouter')->first();
        $deepseek = AiProvider::where('slug', 'deepseek')->first();
        $anthropic = AiProvider::where('slug', 'anthropic')->first();
        $mistral = AiProvider::where('slug', 'mistral')->first();

        // Gemini Models
        if ($gemini) {
            $models = [
                ['model_name' => 'gemini-2.0-flash', 'is_active' => true, 'is_free' => true, 'context_length' => 1000000],
                ['model_name' => 'gemini-1.5-flash', 'is_active' => true, 'is_free' => true, 'context_length' => 1000000],
                ['model_name' => 'gemini-1.5-pro', 'is_active' => true, 'is_free' => false, 'context_length' => 2000000],
            ];
            foreach ($models as $model) {
                $gemini->models()->updateOrCreate(['model_name' => $model['model_name']], $model);
            }
        }

        // Groq Models
        if ($groq) {
            $models = [
                ['model_name' => 'llama-3.3-70b-versatile', 'is_active' => true, 'is_free' => true, 'context_length' => 128000],
                ['model_name' => 'llama3-70b-8192', 'is_active' => true, 'is_free' => true, 'context_length' => 8192],
                ['model_name' => 'mixtral-8x7b-32768', 'is_active' => true, 'is_free' => true, 'context_length' => 32768],
                ['model_name' => 'deepseek-r1-distill-llama-70b', 'is_active' => true, 'is_free' => true, 'context_length' => 128000],
            ];
            foreach ($models as $model) {
                $groq->models()->updateOrCreate(['model_name' => $model['model_name']], $model);
            }
        }

        // DeepSeek Models
        if ($deepseek) {
            $models = [
                ['model_name' => 'deepseek-chat', 'is_active' => true, 'is_free' => false, 'context_length' => 64000],
                ['model_name' => 'deepseek-reasoner', 'is_active' => true, 'is_free' => false, 'context_length' => 64000],
            ];
            foreach ($models as $model) {
                $deepseek->models()->updateOrCreate(['model_name' => $model['model_name']], $model);
            }
        }

        // Anthropic Models
        if ($anthropic) {
            $models = [
                ['model_name' => 'claude-3-5-sonnet-latest', 'is_active' => true, 'is_free' => false, 'context_length' => 200000],
                ['model_name' => 'claude-3-5-haiku-latest', 'is_active' => true, 'is_free' => false, 'context_length' => 200000],
            ];
            foreach ($models as $model) {
                $anthropic->models()->updateOrCreate(['model_name' => $model['model_name']], $model);
            }
        }

        // Mistral Models
        if ($mistral) {
            $models = [
                ['model_name' => 'mistral-large-latest', 'is_active' => true, 'is_free' => false, 'context_length' => 128000],
                ['model_name' => 'mistral-small-latest', 'is_active' => true, 'is_free' => false, 'context_length' => 32000],
                ['model_name' => 'open-mixtral-8x22b', 'is_active' => true, 'is_free' => false, 'context_length' => 64000],
            ];
            foreach ($models as $model) {
                $mistral->models()->updateOrCreate(['model_name' => $model['model_name']], $model);
            }
        }

        // OpenRouter Models - Fixed names
        if ($openrouter) {
            $models = [
                ['model_name' => 'google/gemini-2.0-flash-001', 'is_active' => true, 'is_free' => true, 'context_length' => 1000000],
                ['model_name' => 'deepseek/deepseek-chat', 'is_active' => true, 'is_free' => false, 'context_length' => 64000],
                ['model_name' => 'anthropic/claude-3.5-sonnet', 'is_active' => true, 'is_free' => false, 'context_length' => 200000],
                ['model_name' => 'google/gemini-flash-1.5', 'is_active' => false, 'is_free' => true, 'context_length' => 1000000], // Deactivating old one
            ];
            foreach ($models as $model) {
                $openrouter->models()->updateOrCreate(['model_name' => $model['model_name']], $model);
            }
        }
    }
}
