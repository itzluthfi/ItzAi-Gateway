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

class AiOcr extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-eye';

    protected static string|UnitEnum|null $navigationGroup = 'Playground';

    protected static ?string $navigationLabel = 'AI OCR / Vision';

    protected static ?string $title = 'AI Image Analysis & OCR';

    protected string $view = 'filament.pages.ai-ocr';

    public ?array $data = [];
    public ?string $result = null;
    public bool $isLoading = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Input Gambar')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('provider_id')
                                    ->label('Provider (Pilih yang support Vision)')
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
                        FileUpload::make('image')
                            ->label('Upload Gambar')
                            ->image()
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function processImage()
    {
        $formData = $this->form->getState();
        $this->isLoading = true;
        $this->result = null;

        try {
            $this->result = "Fitur Vision/OCR sedang dalam pengembangan untuk driver ini. \n\nLogika: Gambar " . basename($formData['image']) . " akan dikirim ke " . $formData['model_id'];
        } catch (\Exception $e) {
            $this->result = 'Error: ' . $e->getMessage();
        }

        $this->isLoading = false;
    }
}
