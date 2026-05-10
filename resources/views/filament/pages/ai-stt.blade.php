<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left: Upload Audio -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <form wire:submit="transcribe" class="space-y-4">
                {{ $this->form }}

                <div class="flex justify-end">
                    <x-filament::button type="submit" icon="heroicon-m-microphone" wire:loading.attr="disabled">
                        Transkripsi Audio
                    </x-filament::button>
                </div>
            </form>
        </div>

        <!-- Right: Transcription Result -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm flex flex-col gap-4">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Hasil Transkripsi</h3>
            
            <div class="flex-1 min-h-[300px] bg-gray-50 dark:bg-gray-950 rounded-lg p-4 border border-dashed border-gray-300 dark:border-gray-700 overflow-y-auto relative">
                @if($isLoading)
                    <div class="flex flex-col items-center justify-center h-full gap-4 text-gray-500">
                        <x-filament::loading-indicator class="h-10 w-10" />
                        <p class="animate-pulse">Sedang mentranskripsi audio...</p>
                    </div>
                @elseif($transcription)
                    <div class="prose prose-sm dark:prose-invert max-w-none whitespace-pre-wrap">
                        {{ $transcription }}
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800 flex justify-end">
                        <x-filament::button color="gray" size="sm" icon="heroicon-o-clipboard" onclick="navigator.clipboard.writeText('{{ addslashes($transcription) }}')">
                            Salin Teks
                        </x-filament::button>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full text-gray-400 gap-2">
                        <x-heroicon-o-musical-note class="h-12 w-12 opacity-20" />
                        <p>Hasil transkripsi akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
