<x-app-layout>
    <x-slot name="header">
        <h2>Configuracion de usuario</h2>
    </x-slot>
    <x-guest-layout>
        <form method="POST" action="{{ route('user.update')}}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nombre')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ Auth::user()->name }}" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Surname -->
            <div class="mt-4">
                <x-input-label for="surname" :value="__('Apellidos')" />
                <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" value="{{Auth::user()->surname}}" required autofocus autocomplete="surname" />
                <x-input-error :messages="$errors->get('surname')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{Auth::user()->email}}" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->




            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Actualizar') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>
</x-app-layout>