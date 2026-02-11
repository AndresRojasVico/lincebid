<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex ">
            {{-- BLOQUE DE INFORMACION DEL USUSARO  --}}
            <div class="max-w-xs bg-white overflow-hidden shadow-sm sm:rounded-lg m-3">
                <div class="p-6 text-gray-900 flex flex-col items-center">
                    <img src="https://picsum.photos/200/300/?blur" alt="Avatar" class="rounded-full w-20 h-20 mb-4 ">
                    @if(isset($message))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                    @endif
                    <h3 class="text-2xl text-red-600 font-bold">Hola {{ Auth::user()->name }}</h3>
                    @if (Auth::user()->isAdmin())
                    <a href="{{ route('user.admin') }}">Zona de administrador</a>
                    @else
                    <h3>User</h3>
                    @endif
                    <br>
                    <a href="{{ route('proyectos.index') }}">Buscar proyectos nuevos</a>
                    <br>
                    <a href="{{ route ('proyectosUrl')}}">Proyecto desde url aton de hacienda</a>
                </div>
            </div>
            <div class="max-w-xs bg-white overflow-hidden shadow-sm sm:rounded-lg m-3">
                <div class="p-6 text-gray-900 flex flex-col items-center">
                    buscar proyectos en los que estoy participando
                </div>
            </div>


        </div>
    </div>
</x-app-layout>