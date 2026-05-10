<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <form wire:submit="sendRequest" class="space-y-6">
                {{ $this->form }}

                <div class="flex justify-end gap-x-3">
                    <x-filament::button type="submit" icon="heroicon-m-bolt" wire:loading.attr="disabled">
                        Send Request
                    </x-filament::button>
                </div>
            </form>
        </div>

        @if($isLoading || $response)
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <h3 class="text-lg font-bold mb-4">Response</h3>

            @if($isLoading)
                <div class="flex items-center justify-center py-12">
                    <x-filament::loading-indicator class="h-12 w-12" />
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div @class([
                            'px-3 py-1 rounded-full text-xs font-bold uppercase',
                            'bg-green-100 text-green-700' => str_starts_with($response['status'], '2'),
                            'bg-yellow-100 text-yellow-700' => str_starts_with($response['status'], '4'),
                            'bg-red-100 text-red-700' => str_starts_with($response['status'], '5') || $response['status'] === 'ERROR',
                        ])>
                            Status: {{ $response['status'] }}
                        </div>
                        <div class="px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-full text-xs font-bold uppercase text-gray-600 dark:text-gray-400">
                            Time: {{ $response['duration'] ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-gray-500 uppercase">Response Body</span>
                            <pre class="bg-gray-50 dark:bg-gray-950 p-4 rounded-lg overflow-x-auto text-[13px] font-mono border border-gray-200 dark:border-gray-800">{{ is_array($response['body']) ? json_encode($response['body'], JSON_PRETTY_PRINT) : $response['body'] }}</pre>
                        </div>
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-gray-500 uppercase">Response Headers</span>
                            <pre class="bg-gray-50 dark:bg-gray-950 p-4 rounded-lg overflow-x-auto text-[13px] font-mono border border-gray-200 dark:border-gray-800">{{ json_encode($response['headers'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @endif
    </div>
</x-filament-panels::page>
