<div x-data="{ tab: 'gemini' }" class="p-4">
    <!-- Tab Buttons -->
    <div class="flex border-b border-gray-200 dark:border-gray-700 mb-4">
        <button 
            @click="tab = 'gemini'" 
            :class="{ 'border-primary-500 text-primary-600': tab === 'gemini', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'gemini' }"
            class="py-2 px-4 border-b-2 font-medium text-sm transition-colors duration-200 focus:outline-none"
        >
            Gemini
        </button>
        <button 
            @click="tab = 'groq'" 
            :class="{ 'border-primary-500 text-primary-600': tab === 'groq', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'groq' }"
            class="py-2 px-4 border-b-2 font-medium text-sm transition-colors duration-200 focus:outline-none"
        >
            Groq
        </button>
        <button 
            @click="tab = 'openrouter'" 
            :class="{ 'border-primary-500 text-primary-600': tab === 'openrouter', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'openrouter' }"
            class="py-2 px-4 border-b-2 font-medium text-sm transition-colors duration-200 focus:outline-none"
        >
            OpenRouter
        </button>
    </div>

    <!-- Tab Content -->
    <div>
        <!-- Gemini -->
        <div x-show="tab === 'gemini'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            <h3 class="text-lg font-bold mb-2">Google Gemini API</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm">
                <li>Buka <a href="https://aistudio.google.com/" target="_blank" class="text-primary-600 underline">Google AI Studio</a>.</li>
                <li>Login dengan akun Google Anda.</li>
                <li>Klik tombol <strong>"Get API key"</strong> di sidebar kiri.</li>
                <li>Klik <strong>"Create API key in new project"</strong>.</li>
                <li>Salin API Key yang muncul dan tempelkan di form.</li>
            </ol>
            <div class="mt-4 p-2 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 text-xs">
                <strong>Info:</strong> Gemini menawarkan kuota gratis yang cukup besar untuk penggunaan personal.
            </div>
        </div>

        <!-- Groq -->
        <div x-show="tab === 'groq'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
            <h3 class="text-lg font-bold mb-2">Groq Cloud API</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm">
                <li>Kunjungi <a href="https://console.groq.com/keys" target="_blank" class="text-primary-600 underline">Groq Console</a>.</li>
                <li>Login atau buat akun baru.</li>
                <li>Klik tombol <strong>"Create API Key"</strong>.</li>
                <li>Beri nama kunci Anda (misal: ItzAI Gateway).</li>
                <li>Salin kunci tersebut (hanya muncul sekali!).</li>
            </ol>
            <div class="mt-4 p-2 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 text-xs">
                <strong>Info:</strong> Groq sangat cepat dan mendukung model Llama 3 serta Mixtral secara gratis.
            </div>
        </div>

        <!-- OpenRouter -->
        <div x-show="tab === 'openrouter'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
            <h3 class="text-lg font-bold mb-2">OpenRouter API</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm">
                <li>Buka <a href="https://openrouter.ai/settings/keys" target="_blank" class="text-primary-600 underline">OpenRouter Settings</a>.</li>
                <li>Login ke akun OpenRouter Anda.</li>
                <li>Klik tombol <strong>"Create Key"</strong>.</li>
                <li>Salin API Key yang dibuat.</li>
            </ol>
            <div class="mt-4 p-2 bg-purple-50 dark:bg-purple-900/20 border-l-4 border-purple-400 text-xs">
                <strong>Info:</strong> OpenRouter memberikan akses ke banyak model sekaligus, termasuk model-model gratis.
            </div>
        </div>
    </div>
</div>
