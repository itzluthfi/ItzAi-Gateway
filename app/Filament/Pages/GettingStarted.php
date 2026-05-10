<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class GettingStarted extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Cara Pakai';

    protected static string|\UnitEnum|null $navigationGroup = 'Umum';

    protected static ?string $title = 'Cara Penggunaan ItzAI Gateway';

    protected static ?int $navigationSort = -1;

    protected string $view = 'filament.pages.getting-started';
}
