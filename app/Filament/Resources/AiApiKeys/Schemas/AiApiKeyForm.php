<?php

namespace App\Filament\Resources\AiApiKeys\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AiApiKeyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('provider_id')
                    ->required()
                    ->numeric(),
                Textarea::make('api_key')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive', 'limited' => 'Limited'])
                    ->default('active')
                    ->required(),
                TextInput::make('priority')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('usage_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('error_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('cooldown_until'),
                DateTimePicker::make('last_used_at'),
            ]);
    }
}
