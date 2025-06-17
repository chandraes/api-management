<div>
    <section class="mt-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="space-y-4">

                <div class="flex flex-col space-y-2">
                    <label for="user">Pilih User:</label>
                    <select wire:model="selectedUserId" class="rounded border-gray-300">
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                @if($selectedUserId)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 border p-4 rounded-md shadow-sm">
                    @foreach($availableEndpoints as $endpoint)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model="selectedEndpointIds" value="{{ $endpoint->id }}">
                        <span class="text-sm font-mono">
                            [{{ $endpoint->method }}] {{ $endpoint->uri }}
                        </span>
                    </label>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button wire:click="save" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Simpan Akses
                    </button>
                </div>
                @endif

                @if (session()->has('success'))
                <div class="text-green-600">{{ session('success') }}</div>
                @endif
                @if (session()->has('error'))
                <div class="text-red-600">{{ session('error') }}</div>
                @endif
            </div>
        </div>
    </section>
</div>
