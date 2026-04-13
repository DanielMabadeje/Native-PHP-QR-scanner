<div class="flex flex-col min-h-screen bg-gray-950">

    {{-- Header --}}
    <header class="px-4 py-5 pt-safe">
        <h1 class="text-2xl font-bold text-white">QR Scanner</h1>
        <p class="text-gray-400 text-sm mt-0.5">Scan and save QR codes & barcodes</p>
    </header>

    {{-- Status --}}
    @if ($status)
        <div class="mx-4 mb-3 bg-blue-900/50 border border-blue-700 text-blue-300 rounded-2xl px-4 py-2.5 text-sm">
            {{ $status }}
        </div>
    @endif

    {{-- Scan button --}}
    <div class="px-4 mb-6">
        <button wire:click="startScan"
            class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-5 rounded-3xl
                   shadow-lg shadow-blue-900/50 active:scale-95 transition-transform flex items-center justify-center gap-3 text-lg">
            <span class="text-2xl">📷</span> Scan Code
        </button>
    </div>

    {{-- Scans list --}}
    <div class="flex-1 px-4 space-y-3 pb-10">

        @if ($scans->isNotEmpty())
            <div class="flex items-center justify-between mb-1">
                <p class="text-gray-400 text-sm font-medium">{{ $scans->count() }} scan{{ $scans->count() === 1 ? '' : 's' }}</p>
                <button wire:click="clearAll" wire:confirm="Clear all scans?"
                    class="text-red-400 text-xs font-medium px-3 py-1 rounded-lg bg-red-900/30">
                    Clear all
                </button>
            </div>
        @endif

        @forelse ($scans as $scan)
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4">

                {{-- Type badge + format --}}
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ $scan->isUrl() ? 'bg-green-900/60 text-green-400' : 'bg-gray-800 text-gray-400' }}">
                        {{ $scan->isUrl() ? '🔗 URL' : '📝 Text' }}
                    </span>
                    <span class="text-xs text-gray-600 uppercase">{{ $scan->format }}</span>
                    <span class="text-xs text-gray-600 ml-auto">{{ $scan->created_at->diffForHumans() }}</span>
                </div>

                {{-- Data --}}
                <p class="text-white text-sm break-all leading-relaxed">{{ $scan->data }}</p>

                {{-- Actions --}}
                <div class="flex gap-2 mt-3">
                    @if ($scan->isUrl())
                        <a href="{{ $scan->data }}" target="_blank"
                            class="flex-1 bg-blue-600 text-white text-xs font-semibold py-2 rounded-xl text-center">
                            Open URL
                        </a>
                    @endif
                    <button
                        @click="navigator.clipboard?.writeText('{{ addslashes($scan->data) }}').then(() => alert('Copied!'))"
                        class="flex-1 bg-gray-800 text-gray-300 text-xs font-semibold py-2 rounded-xl">
                        Copy
                    </button>
                    <button wire:click="deleteScan({{ $scan->id }})"
                        class="bg-red-900/40 text-red-400 text-xs font-semibold px-3 py-2 rounded-xl">
                        🗑
                    </button>
                </div>

            </div>
        @empty
            <div class="text-center py-24 text-gray-600">
                <p class="text-5xl mb-4">🔍</p>
                <p class="text-lg font-medium text-gray-500">No scans yet</p>
                <p class="text-sm mt-1">Tap Scan Code to get started</p>
            </div>
        @endforelse

    </div>

</div>