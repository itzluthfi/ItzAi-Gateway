<?php

namespace App\Filament\Resources\AiModels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AiModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('provider_id')
                    ->required()
                    ->numeric(),
                TextInput::make('model_name')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_free')
                    ->required(),
                TextInput::make('context_length')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
