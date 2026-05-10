<x-filament-panels::page>
    <div class="fi-section-content-ctn" style="display: grid; gap: 2rem;">
        
        <!-- Header -->
        <div style="background: #fff; padding: 2rem; border-radius: 1rem; border: 1px solid #eee; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: rgb(var(--primary-600)); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <x-filament::icon icon="heroicon-o-sparkles" style="width: 24px; height: 24px; color: rgb(var(--primary-600));" />
                Selamat Datang di ItzAI Gateway!
            </h2>
            <p style="color: #666;">
                Sistem orkestrasi AI yang menggabungkan banyak provider (Gemini, Groq, OpenRouter) ke dalam satu endpoint API yang stabil.
            </p>
        </div>

        <!-- Langkah-langkah -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            
            <!-- Step 1 -->
            <div style="background: #fff; padding: 1.5rem; border-radius: 1rem; border: 1px solid #eee;">
                <div style="width: 40px; height: 40px; background: rgb(var(--primary-50)); color: rgb(var(--primary-600)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 1rem;">1</div>
                <h3 style="font-weight: bold; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <x-filament::icon icon="heroicon-o-server-stack" style="width: 20px; height: 20px; color: #888;" />
                    Tambah Provider
                </h3>
                <p style="font-size: 0.875rem; color: #666; margin-bottom: 1rem;">Daftarkan provider AI yang ingin digunakan (Gemini, Groq, dll).</p>
                <a href="{{ \App\Filament\Resources\AiProviders\AiProviderResource::getUrl('create') }}" style="color: rgb(var(--primary-600)); font-weight: 600; text-decoration: none;">Tambah Provider &rarr;</a>
            </div>

            <!-- Step 2 -->
            <div style="background: #fff; padding: 1.5rem; border-radius: 1rem; border: 1px solid #eee;">
                <div style="width: 40px; height: 40px; background: rgb(var(--primary-50)); color: rgb(var(--primary-600)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 1rem;">2</div>
                <h3 style="font-weight: bold; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <x-filament::icon icon="heroicon-o-key" style="width: 20px; height: 20px; color: #888;" />
                    Input API Keys
                </h3>
                <p style="font-size: 0.875rem; color: #666; margin-bottom: 1rem;">Masukkan API Key untuk provider tersebut agar bisa dirotasi otomatis.</p>
                <a href="{{ \App\Filament\Resources\AiApiKeys\AiApiKeyResource::getUrl('create') }}" style="color: rgb(var(--primary-600)); font-weight: 600; text-decoration: none;">Tambah API Key &rarr;</a>
            </div>

            <!-- Step 3 -->
            <div style="background: #fff; padding: 1.5rem; border-radius: 1rem; border: 1px solid #eee;">
                <div style="width: 40px; height: 40px; background: rgb(var(--primary-50)); color: rgb(var(--primary-600)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 1rem;">3</div>
                <h3 style="font-weight: bold; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <x-filament::icon icon="heroicon-o-cpu-chip" style="width: 20px; height: 20px; color: #888;" />
                    Daftarkan Model
                </h3>
                <p style="font-size: 0.875rem; color: #666; margin-bottom: 1rem;">Daftarkan model spesifik (contoh: <code>gemini-1.5-flash</code>).</p>
                <a href="{{ \App\Filament\Resources\AiModels\AiModelResource::getUrl('create') }}" style="color: rgb(var(--primary-600)); font-weight: 600; text-decoration: none;">Tambah Model &rarr;</a>
            </div>

            <!-- Step 4 -->
            <div style="background: #fff; padding: 1.5rem; border-radius: 1rem; border: 1px solid #eee;">
                <div style="width: 40px; height: 40px; background: rgb(var(--primary-50)); color: rgb(var(--primary-600)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 1rem;">4</div>
                <h3 style="font-weight: bold; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <x-filament::icon icon="heroicon-o-rocket-launch" style="width: 20px; height: 20px; color: #888;" />
                    Gunakan Endpoint
                </h3>
                <p style="font-size: 0.875rem; color: #666; margin-bottom: 0.5rem;">Tembak API Gateway ini via:</p>
                <div style="background: #f8f8f8; padding: 0.5rem; border-radius: 0.5rem; font-family: monospace; font-size: 0.75rem; color: rgb(var(--primary-600)); overflow-x: auto;">
                    POST {{ url('/api/chat') }}
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
