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
                Select::make('provider_id')
                    ->relationship('provider', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih provider AI untuk API key ini.'),
                Textarea::make('api_key')
                    ->required()
                    ->columnSpanFull()
                    ->hintAction(
                        \Filament\Actions\Action::make('get_api_key_info')
                            ->icon('heroicon-m-question-mark-circle')
                            ->label('Cara mendapatkan API Key?')
                            ->modalHeading('Panduan Mendapatkan API Key')
                            ->modalContent(view('filament.pages.api-key-guide'))
                            ->modalSubmitAction(false)
                    )
                    ->helperText('Masukkan API key yang valid dari provider.'),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive', 'limited' => 'Limited'])
                    ->default('active')
                    ->required()
                    ->helperText('Status saat ini dari API key.'),
                TextInput::make('priority')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->helperText('Prioritas penggunaan (angka lebih tinggi = prioritas lebih tinggi).'),
                TextInput::make('usage_count')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->helperText('Total penggunaan API key ini.'),
                TextInput::make('error_count')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->helperText('Total error yang terjadi pada API key ini.'),
                DateTimePicker::make('cooldown_until')
                    ->helperText('Waktu sampai API key bisa digunakan kembali setelah kena limit.'),
                DateTimePicker::make('last_used_at')
                    ->disabled()
                    ->helperText('Waktu terakhir API key digunakan.'),
            ]);
    }
}
