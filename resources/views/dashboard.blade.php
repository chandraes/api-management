<x-layouts.app :title="__('Dashboard')">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Total Users --}}
        <a href="{{ route('users') }}"
           class="relative aspect-video flex items-center justify-center rounded-xl border border-neutral-200
                  dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
            <div class="text-center">
                <div class="text-lg text-neutral-600 dark:text-neutral-300">Total Users</div>
                <div class="text-4xl font-bold">{{ $totalUsers }}</div>
            </div>
        </a>

        {{-- Total Endpoints --}}
        <a href="{{ route('admin.endpoint-access') }}"
           class="relative aspect-video flex items-center justify-center rounded-xl border border-neutral-200
                  dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
            <div class="text-center">
                <div class="text-lg text-neutral-600 dark:text-neutral-300">Total Endpoints</div>
                <div class="text-4xl font-bold">{{ $totalApiEndpoints }}</div> 
            </div>
        </a>

        {{-- Placeholder --}}
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>

    </div>

</x-layouts.app>
