<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Http;
use UnitEnum;

class ApiTester extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-command-line';

    protected static string|UnitEnum|null $navigationGroup = 'Playground';

    protected static ?string $navigationLabel = 'Raw API Tester';

    protected static ?string $title = 'HTTP Request Tester';

    protected string $view = 'filament.pages.api-tester';

    public ?array $data = [];
    public ?array $response = null;
    public bool $isLoading = false;

    public function mount(): void
    {
        $this->form->fill([
            'method' => 'POST',
            'url' => url('/api/v1/chat'),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode(['model' => 'gemini-1.5-flash', 'message' => 'Hello!'], JSON_PRETTY_PRINT),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Grid::make(3)
                    ->schema([
                        Select::make('method')
                            ->options(['GET' => 'GET', 'POST' => 'POST', 'PUT' => 'PUT', 'DELETE' => 'DELETE'])
                            ->required(),
                        TextInput::make('url')
                            ->label('URL Endpoint')
                            ->required()
                            ->columnSpan(2),
                    ]),
                KeyValue::make('headers')
                    ->label('Headers'),
                Textarea::make('body')
                    ->label('Request Body (JSON)')
                    ->rows(8),
            ])
            ->statePath('data');
    }

    public function sendRequest()
    {
        $formData = $this->form->getState();
        $this->isLoading = true;
        $this->response = null;

        try {
            $method = $formData['method'];
            $url = $formData['url'];
            $headers = $formData['headers'] ?? [];
            $body = json_decode($formData['body'], true) ?? [];

            $startTime = microtime(true);
            
            $request = Http::withHeaders($headers);
            
            if ($method === 'GET') {
                $res = $request->get($url, $body);
            } else {
                $res = $request->send($method, $url, ['json' => $body]);
            }

            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->response = [
                'status' => $res->status(),
                'duration' => $duration . ' ms',
                'headers' => $res->headers(),
                'body' => $res->json() ?? $res->body(),
            ];
        } catch (\Exception $e) {
            $this->response = [
                'status' => 'ERROR',
                'body' => $e->getMessage(),
            ];
        }

        $this->isLoading = false;
    }
}
