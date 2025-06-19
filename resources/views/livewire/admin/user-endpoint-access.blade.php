<div>
    <section class="mt-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Kelola Akses Endpoint User</h2>

                {{-- Flash Message --}}
                @if (session()->has('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                    {{ session('success') }}
                </div>
                @elseif (session()->has('error'))
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                    {{ session('error') }}
                </div>
                @endif

                {{-- Pilih User --}}
                <div class="mb-4">
                    <label for="user" class="block font-medium mb-1">Pilih User:</label>
                    <select wire:model.live="selectedUserId" id="user" class="w-full border rounded p-2">
                        <option value="">-- Pilih User --</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search Endpoint --}}
                @if ($selectedUserId)
                <div class="mb-4">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari endpoint (uri atau method)..." class="w-full border px-3 py-2 rounded" />
                </div>

                {{-- List Endpoint --}}
                <div class="mb-4">
                    <label class="block font-medium mb-1">Pilih Endpoint yang Diizinkan:</label>
                    <div class="border rounded p-2 max-h-80 overflow-y-auto space-y-2 bg-white shadow-inner">
                        @forelse ($availableEndpoints as $endpoint)
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="selectedEndpointIds" value="{{ $endpoint->id }}"
                                id="endpoint_{{ $endpoint->id }}" class="form-checkbox text-blue-600">
                            <label for="endpoint_{{ $endpoint->id }}" class="cursor-pointer text-sm">
                                <span class="font-mono text-gray-700">[{{ strtoupper($endpoint->method) }}]</span>
                                <span class="ml-1 text-gray-700"">{{ $endpoint->uri }}</span>
                            </label>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">Tidak ada endpoint ditemukan.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <button wire:click="save"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-150">
                    Simpan Akses
                </button>
                @endif
            </div>
        </div>
    </section>
</div>
