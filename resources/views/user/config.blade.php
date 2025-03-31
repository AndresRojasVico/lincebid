<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Configuracion de usuario</h2>
    </x-slot>
    <x-guest-layout>

        @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
            @endif
            <form method="POST" action="{{ route('user.update')}}" enctype="multipart/form-data" class="space-y-6">
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

                <!-- Imagen de usuario -->
                @include('includes.avatar')


                <div class="mt-4">
                    <x-input-label for="image_path" :value="__('Avatar')" />
                    <x-text-input id="image_path" class="block mt-1 w-full" type="file" name="image_path" />
                    <x-input-error :messages="$errors->get('image_path')" class="mt-2" />
                </div>



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