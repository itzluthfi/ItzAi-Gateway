<?php

namespace Database\Seeders;

use App\Models\AiSetting;
use Illuminate\Database\Seeder;

class AiSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'ItzAI Gateway', 'group' => 'branding'],
            ['key' => 'default_provider_id', 'value' => '1', 'group' => 'ai'],
            ['key' => 'default_model_name', 'value' => 'gemini-2.0-flash', 'group' => 'ai'],
            ['key' => 'failover_enabled', 'value' => '1', 'group' => 'failover'],
            ['key' => 'max_retries', 'value' => '3', 'group' => 'failover'],
            ['key' => 'log_retention_days', 'value' => '30', 'group' => 'monitoring'],
        ];

        foreach ($settings as $setting) {
            AiSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
