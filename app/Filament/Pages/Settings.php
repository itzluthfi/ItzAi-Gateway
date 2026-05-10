<?php

namespace App\Filament\Pages;

use App\Models\AiProvider;
use App\Models\AiSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $title = 'Pengaturan Sistem';

    protected string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = AiSetting::all()->pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Branding')
                    ->description('Pengaturan identitas aplikasi.')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Nama Aplikasi')
                            ->default('ItzAI Gateway'),
                    ]),

                Section::make('Default AI')
                    ->description('Pengaturan fallback jika tidak ada provider/model yang ditentukan.')
                    ->schema([
                        Select::make('default_provider_id')
                            ->label('Default Provider')
                            ->options(AiProvider::all()->pluck('name', 'id'))
                            ->searchable(),
                        TextInput::make('default_model_name')
                            ->label('Default Model')
                            ->placeholder('misal: gemini-1.5-flash'),
                    ]),

                Section::make('Logika Failover')
                    ->description('Cara sistem menangani error dan limit.')
                    ->schema([
                        Toggle::make('failover_enabled')
                            ->label('Aktifkan Failover')
                            ->default(true),
                        TextInput::make('max_retries')
                            ->label('Maksimal Retry')
                            ->numeric()
                            ->default(3),
                        TextInput::make('log_retention_days')
                            ->label('Retensi Log (Hari)')
                            ->numeric()
                            ->default(30),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            foreach ($data as $key => $value) {
                AiSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'group' => 'general']
                );
            }

            Notification::make()
                ->title('Berhasil!')
                ->body('Pengaturan telah disimpan.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal!')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
