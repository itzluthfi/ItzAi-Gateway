<?php

namespace App\Filament\Resources\AiConversations;

use App\Filament\Resources\AiConversations\Pages\ManageAiConversations;
use App\Models\AiConversation;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AiConversationResource extends Resource
{
    protected static ?string $model = AiConversation::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Grid::make(2)
                    ->schema([
                        TextInput::make('user_id')
                            ->label('User ID')
                            ->disabled(),
                        TextInput::make('session_id')
                            ->label('Session ID')
                            ->disabled(),
                        TextInput::make('role')
                            ->label('Role (User/Assistant)')
                            ->disabled(),
                        TextInput::make('model')
                            ->label('Model used')
                            ->disabled(),
                        \Filament\Forms\Components\Select::make('provider_id')
                            ->label('Provider')
                            ->relationship('provider', 'name')
                            ->disabled(),
                    ]),
                Textarea::make('message')
                    ->label('Message Content')
                    ->rows(5)
                    ->disabled()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('session_id')
                    ->searchable(),
                TextColumn::make('role')
                    ->searchable(),
                TextColumn::make('provider_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAiConversations::route('/'),
        ];
    }
}
