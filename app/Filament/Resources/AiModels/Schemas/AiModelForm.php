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
                \Filament\Forms\Components\Select::make('provider_id')
                    ->relationship('provider', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih provider AI untuk model ini.'),
                TextInput::make('model_name')
                    ->required()
                    ->helperText('Nama identitas model (misal: gemini-1.5-flash).'),
                Toggle::make('is_active')
                    ->required()
                    ->default(true)
                    ->helperText('Aktifkan atau nonaktifkan model ini.'),
                Toggle::make('is_free')
                    ->required()
                    ->default(true)
                    ->helperText('Tandai jika model ini gratis digunakan.'),
                TextInput::make('context_length')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->helperText('Panjang konteks maksimum model (token).'),
            ]);
    }
}
