<div class="flex items-center justify-center">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md dark:bg-neutral-800">
        <h2 class="mb-4 text-xl font-semibold text-center text-neutral-900 dark:text-neutral-200">{{ __('Create User') }}</h2>
        <form wire:submit.prevent="storePrompt">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-900 dark:text-neutral-200">{{ __('Name') }}</label>
                    <input wire:model="name" type="text" id="name" class="mt-1 block
                        w-full px-3 py-2 text-sm text-neutral-900 placeholder-neutral-300 border border-neutral-300 rounded-lg
                        focus:outline-none focus:border-cyan-600 dark:text-neutral-200 dark:placeholder-neutral-700
                        dark:border-neutral-700 dark:bg-neutral-800" placeholder="{{ __('Name') }}" >
                    @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                {{-- select role --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-neutral-900 dark:text-neutral-200">{{ __('Role') }}</label>
                    <select wire:model="role" id="role" class="mt-1 block
                        w-full px-3 py-2 text-sm text-neutral-900 placeholder-neutral-300 border border-neutral-300 rounded-lg
                        focus:outline-none focus:border-cyan-600 dark:text-neutral-200 dark:placeholder-neutral-700
                        dark:border-neutral-700 dark:bg-neutral-800">
                        <option value="">{{ __('Select Role') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}">{{ Str::upper($role) }}</option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-neutral-900 dark:text-neutral-200">{{ __('Email') }}</label>
                    <input wire:model.blur="email" type="email" id="email" class="mt-1 block
                        w-full px-3 py-2 text-sm text-neutral-900 placeholder-neutral-300 border border-neutral-300 rounded-lg
                        focus:outline-none focus:border-cyan-600 dark:text-neutral-200 dark:placeholder-neutral-700
                        dark:border-neutral-700 dark:bg-neutral-800" placeholder="{{ __('Email') }}" >
                    @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-neutral-900 dark:text-neutral-200">{{ __('Password') }}</label>
                    <input wire:model="password" type="password" id="password" class="mt-1 block
                        w-full px-3 py-2 text-sm text-neutral-900 placeholder-neutral-300 border border-neutral-300 rounded-lg
                        focus:outline-none focus:border-cyan-600 dark:text-neutral-200 dark:placeholder-neutral-700
                        dark:border-neutral-700 dark:bg-neutral-800" placeholder="{{ __('Password') }}" >
                    @error('password') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex justify-end mt-4">

                <button type="submit" class="px-4 py-2 text-sm font-medium bg-cyan-600 border border-transparent rounded-lg
                    hover:bg-cyan-700 focus:outline-none focus:border-cyan-700 focus:ring focus:ring-cyan-200 dark:text-neutral-200
                    dark:bg-cyan-700 dark:hover:bg-cyan-800 dark:focus:border-cyan-700 dark:focus:ring-cyan-200">
                    {{ __('Create') }}
                </button>

                {{-- cancel button with grey color--}}
                <a href="{{route('users')}}" wire:navigate class="px-4 py-2 mx-3 text-sm font-medium bg-neutral-300 border border-transparent rounded-lg
                    hover:bg-neutral-400 focus:outline-none focus:border-neutral-400 focus:ring focus:ring-neutral-200 dark:text-neutral-200
                    dark:bg-neutral-700 dark:hover:bg-neutral-800 dark:focus:border-neutral-400 dark:focus:ring-neutral-200">
                    {{ __('Cancel') }}
                </a>


            </div>
        </form>
    </div>
</div>
