<div>
    <section class="mt-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-neutral-200"><i class="fas fa-users me-4"></i>{{ __('Users') }}</h1>
                {{-- create button --}}
                <div class="flex flex-col lg:mt-0 lg:flex-row gap-4 mt-5 ">
                    <flux:button icon-trailing="plus" variant="primary" href="{{route('users.create')}}" wire:navigate>{{ __('Create User') }}</flux:button>
                </div>
                <div class="flex flex-col lg:mt-0 lg:flex-row mt-5 gap-4">
                    <div class="relative">
                        <input
                            wire:model.live.debounce.300ms="search"
                            type="text"
                            class="w-full h-10 pl-4 pr-10 text-sm text-neutral-900 placeholder-neutral-300 border border-neutral-300 rounded-lg focus:outline-none focus:border-primary-600 dark:text-neutral-200 dark:placeholder-neutral-700 dark:border-neutral-700 dark:bg-neutral-800"
                            placeholder="{{ __('Search') }}"
                        />
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            {{-- <x-heroicon-o-search class="w-5 h-5 text-neutral-500" /> --}}
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </div>

                </div>
            </div>
            <div class="mt-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-neutral-900 border border-neutral-300 rounded-lg overflow-hidden shadow-lg dark:border-neutral-700 dark:text-neutral-200 dark:bg-neutral-800">
                        <thead class="text-gray-700 ">
                            <tr>
                                <th wire:click="doSort('name')" class="px-4 py-3">
                                    <x-datatable-sort :sortColumn='$sortColumn' :sortDirection="$sortDirection" columnName="name"/>
                                </th>
                                <th wire:click="doSort('email')" class="px-4 py-3">
                                    <x-datatable-sort :sortColumn='$sortColumn' :sortDirection="$sortDirection" columnName="email"/>
                                </th>
                                <th wire:click="doSort('role')" class="px-4 py-3">
                                    <x-datatable-sort :sortColumn='$sortColumn' :sortDirection="$sortDirection" columnName="role"/>
                                </th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3">{{ $user->name }}</td>
                                    <td class="px-4 py-3">{{ $user->email }}</td>
                                    <td class="px-4 py-3">{{ $user->role }}</td>
                                    <td class="px-4 py-3 items-center">
                                        <div class="flex items-center gap-2">
                                            <flux:button variant="danger" size="sm" wire:click="confirmUserDeletion({{$user->id}})"><i class="fas fa-trash"></i></flux:button>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="py-4 px-3">
                    <div class="flex">
                        <div class="flex space-x-4 items-center mb-3">
                            <label for="">Per Page</label>
                            <select wire:model.live="perPage" class="border border-neutral-300 rounded-lg">
                                @foreach ($page as $p)
                                    <option value="{{ $p }}">{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                {{ $users->links() }}
                <div class="mt-5">

                </div>
            </div>

        </div>
    </section>
    </div>
