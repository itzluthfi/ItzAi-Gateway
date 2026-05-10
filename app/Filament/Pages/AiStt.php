<?php

namespace App\Filament\Pages;

use App\Models\AiModel;
use App\Models\AiProvider;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use UnitEnum;

class AiStt extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-microphone';

    protected static string|UnitEnum|null $navigationGroup = 'Playground';

    protected static ?string $navigationLabel = 'AI Speech to Text';

    protected static ?string $title = 'Speech to Text (Transkripsi)';

    protected string $view = 'filament.pages.ai-stt';

    public ?array $data = [];
    public ?string $transcription = null;
    public bool $isLoading = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Input Audio')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('provider_id')
                                    ->label('Provider (misal: Groq/Whisper)')
                                    ->options(AiProvider::all()->pluck('name', 'id'))
                                    ->reactive()
                                    ->required(),
                                Select::make('model_id')
                                    ->label('Model')
                                    ->options(function (callable $get) {
                                        $providerId = $get('provider_id');
                                        if (! $providerId) return [];
                                        return AiModel::where('provider_id', $providerId)->pluck('model_name', 'model_name');
                                    })
                                    ->required(),
                            ]),
                        FileUpload::make('audio')
                            ->label('Upload File Audio')
                            ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/mp3', 'audio/ogg'])
                            ->maxSize(10240)
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function transcribe()
    {
        $formData = $this->form->getState();
        $this->isLoading = true;
        $this->transcription = null;

        try {
            $this->transcription = "Transkripsi untuk file " . basename($formData['audio']) . " sedang disimulasikan. \n\n" . 
                                  "Hasil: \"Halo, ini adalah contoh hasil transkripsi dari model " . $formData['model_id'] . ". Fitur ini membutuhkan endpoint audio-to-text pada driver.\"";
        } catch (\Exception $e) {
            $this->transcription = 'Error: ' . $e->getMessage();
        }

        $this->isLoading = false;
    }
}
