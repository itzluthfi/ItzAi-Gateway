<x-filament-panels::page>
    <style>
        .chat-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            background: #f9fafb;
            border-radius: 1rem;
            padding: 1.5rem;
            height: 500px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
        }

        .dark .chat-container {
            background: #030712;
            border-color: #1f2937;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.3);
        }

        .message-wrapper {
            display: flex;
            flex-direction: column;
            max-width: 85%;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message-user {
            align-self: flex-end;
        }

        .message-assistant {
            align-self: flex-start;
        }

        .bubble {
            padding: 0.8rem 1.2rem;
            border-radius: 1.2rem;
            font-size: 0.95rem;
            line-height: 1.5;
            position: relative;
        }

        .bubble-user {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #000;
            border-bottom-right-radius: 0.2rem;
            box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2);
        }

        .bubble-assistant {
            background: #ffffff;
            color: #1f2937;
            border-bottom-left-radius: 0.2rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .dark .bubble-assistant {
            background: #111827;
            color: #f3f4f6;
            border-color: #374151;
        }

        .role-label {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 0.3rem;
            letter-spacing: 0.05em;
            color: #6b7280;
        }

        .dark .role-label {
            color: #9ca3af;
        }

        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 10px 15px;
            background: #fff;
            border-radius: 15px;
            width: fit-content;
            border: 1px solid #eee;
        }

        .dark .typing-indicator {
            background: #111827;
            border-color: #374151;
        }

        .dot {
            width: 6px;
            height: 6px;
            background: #999;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .dot:nth-child(1) { animation-delay: -0.32s; }
        .dot:nth-child(2) { animation-delay: -0.16s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1.0); }
        }

        .input-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            margin-top: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .dark .input-card {
            background: #111827;
            border-color: #1f2937;
        }
        
        /* Log Terminal Style */
        .terminal-container {
            background: #1a1a1a;
            color: #d1d5db;
            padding: 1rem;
            border-radius: 0.75rem;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.75rem;
            border: 1px solid #333;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .terminal-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            border-bottom: 1px solid #333;
            padding-bottom: 0.5rem;
            color: #9ca3af;
        }
        
        .log-entry {
            display: flex;
            gap: 0.5rem;
            line-height: 1.6;
            margin-bottom: 2px;
        }
        
        .log-icon {
            flex-shrink: 0;
            width: 14px;
        }
    </style>

    <div class="space-y-4">
        <!-- Chat History -->
        <div class="chat-container" id="chat-container">
            @foreach($messages as $msg)
                <div class="message-wrapper {{ $msg['role'] === 'user' ? 'message-user' : 'message-assistant' }}">
                    <span class="role-label" style="{{ $msg['role'] === 'user' ? 'text-align: right;' : '' }}">
                        @if($msg['role'] === 'user')
                            Anda
                        @else
                            [{{ $msg['provider_name'] ?? 'AI' }}] {{ $msg['model_name'] ?? 'Assistant' }}
                        @endif
                    </span>
                    <div class="bubble {{ $msg['role'] === 'user' ? 'bubble-user' : 'bubble-assistant' }}">
                        <div class="prose prose-sm dark:prose-invert max-w-none">
                            {!! \Illuminate\Support\Str::markdown($msg['content']) !!}
                        </div>
                    </div>
                </div>
            @endforeach

            @if($isLoading)
                <div class="message-wrapper message-assistant">
                    <span class="role-label">System is thinking...</span>
                    <div class="typing-indicator">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>
            @endif
        </div>

        @if(count($processLogs) > 0)
            <div class="terminal-container">
                <div class="terminal-header">
                    <x-heroicon-o-cpu-chip style="width: 14px; height: 14px;" />
                    <span class="uppercase tracking-widest text-[10px] font-bold">Smart Rotation Engine v1.0</span>
                </div>
                <div class="space-y-1">
                    @foreach($processLogs as $log)
                        <div class="log-entry">
                            <span class="log-icon">
                                @if(str_contains($log, '🔄')) <span class="text-blue-400">→</span>
                                @elseif(str_contains($log, '✅')) <span class="text-green-400">✓</span>
                                @elseif(str_contains($log, '❌')) <span class="text-red-400">✗</span>
                                @elseif(str_contains($log, '⚠️')) <span class="text-amber-400">!</span>
                                @else <span class="text-gray-500">•</span>
                                @endif
                            </span>
                            <span class="log-text">
                                {!! preg_replace('/\*\*(.*?)\*\*/', '<span class="text-white font-bold">$1</span>', str_replace(['🔄 ', '✅ ', '❌ ', '⚠️ '], '', $log)) !!}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Control Card -->
        <div class="input-card">
            <form wire:submit="sendMessage" class="space-y-4">
                {{ $this->form }}

                <div class="flex justify-end pt-2">
                    <x-filament::button 
                        type="submit" 
                        size="lg"
                        icon="heroicon-m-paper-airplane" 
                        wire:loading.attr="disabled"
                        style="background: #f59e0b; color: #000; font-weight: bold; border: none; border-radius: 0.5rem;"
                    >
                        <span wire:loading.remove>Kirim Pesan</span>
                        <span wire:loading>Memproses...</span>
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const container = document.getElementById('chat-container');
            container.scrollTop = container.scrollHeight;

            window.addEventListener('contentChanged', () => {
                setTimeout(() => {
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
                }, 100);
            });

            @this.on('start-rotation', () => {
                @this.call('processStep');
            });
        });
    </script>
</x-filament-panels::page>
