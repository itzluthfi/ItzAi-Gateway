<?php

namespace App\Filament\Resources\AiConversations\Pages;

use App\Filament\Resources\AiConversations\AiConversationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAiConversations extends ManageRecords
{
    protected static string $resource = AiConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
