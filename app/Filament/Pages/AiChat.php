<?php

namespace App\Filament\Pages;

use App\Models\AiModel;
use App\Models\AiProvider;
use App\Services\AI\AIManager;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use UnitEnum;

class AiChat extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static string|UnitEnum|null $navigationGroup = 'Playground';

    protected static ?string $navigationLabel = 'AI Chat';

    protected static ?string $title = 'AI Chat Playground';

    protected string $view = 'filament.pages.ai-chat';

    public ?array $data = [];
    public array $messages = [];
    public array $processLogs = [];
    public bool $isLoading = false;

    public function mount(): void
    {
        $this->form->fill([
            'smart_mode' => true,
        ]);
        $this->messages[] = [
            'role' => 'assistant',
            'content' => 'Halo! Saya adalah asisten AI Gateway. Silakan ketik pesan Anda.',
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Toggle::make('smart_mode')
                            ->label('Smart Mode')
                            ->helperText('Otomatis cari provider/model terbaik')
                            ->reactive()
                            ->default(true),
                        Select::make('provider_id')
                            ->label('Provider')
                            ->options(AiProvider::all()->pluck('name', 'id'))
                            ->reactive()
                            ->hidden(fn (callable $get) => $get('smart_mode'))
                            ->placeholder('Otomatis (Rotation)'),
                        Select::make('model_id')
                            ->label('Model')
                            ->options(function (callable $get) {
                                $providerId = $get('provider_id');
                                if (! $providerId) return [];
                                return AiModel::where('provider_id', $providerId)->pluck('model_name', 'model_name');
                            })
                            ->hidden(fn (callable $get) => $get('smart_mode') || ! $get('provider_id'))
                            ->placeholder('Otomatis (Best Model)'),
                    ]),
                Textarea::make('message')
                    ->label('Pesan Anda')
                    ->placeholder('Ketik sesuatu...')
                    ->required()
                    ->rows(3),
            ])
            ->statePath('data');
    }

    public function sendMessage()
    {
        $formData = $this->form->getState();
        $userMessage = $formData['message'];

        $this->messages[] = [
            'role' => 'user',
            'content' => $userMessage,
        ];

        $this->form->fill([
            'smart_mode' => $formData['smart_mode'],
            'provider_id' => $formData['provider_id'] ?? null,
            'model_id' => $formData['model_id'] ?? null,
        ]);
        
        $this->isLoading = true;
        $this->processLogs = [];

        try {
            $aiManager = app(AIManager::class);
            
            $payload = [
                'model' => $formData['model_id'] ?? null,
                'messages' => [
                    ['role' => 'user', 'content' => $userMessage]
                ],
            ];

            // Manual loop for logging purposes in playground
            $providerId = $formData['provider_id'] ?? null;
            $providers = $providerId ? AiProvider::where('id', $providerId)->get() : AiProvider::where('is_active', true)->orderBy('priority', 'desc')->get();

            $success = false;
            foreach ($providers as $p) {
                $this->processLogs[] = "🔄 Mencoba provider: **{$p->name}**...";
                try {
                    $resultData = $aiManager->chat($payload, false, $p->id);
                    $response = $resultData['response'];
                    $actualModel = $resultData['model'];

                    if ($response->successful()) {
                        $this->processLogs[] = "✅ Berhasil dengan **{$p->name}** (Model: {$actualModel})";
                        
                        $result = $response->json();
                        $content = $result['choices'][0]['message']['content'] ?? null;
                        if (! $content) {
                            $content = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
                        }

                        $this->messages[] = [
                            'role' => 'assistant',
                            'content' => $content ?? 'Empty response',
                            'provider_name' => $p->name,
                            'model_name' => $actualModel,
                        ];
                        $success = true;
                        break;
                    } else {
                        $this->processLogs[] = "❌ Gagal: " . ($response->json()['error']['message'] ?? 'Unknown error');
                    }
                } catch (\Exception $e) {
                    $this->processLogs[] = "⚠️ Error: " . $e->getMessage();
                    continue;
                }
            }

            if (! $success) {
                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => 'Maaf, semua provider gagal merespons. Silakan cek log proses di bawah.',
                ];
            }

        } catch (\Exception $e) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => 'Error: ' . $e->getMessage(),
            ];
        }

        $this->isLoading = false;
        $this->dispatch('contentChanged');
    }
}
