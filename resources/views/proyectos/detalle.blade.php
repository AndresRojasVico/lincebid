<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalles del proyecto </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(empty($proyecto))
                    <p>No hay proyectos disponibles.</p>
                    @else
                    <h2>Detalles del proyecto</h2>
                    Id: {{ $proyecto['id'] }} <br>
                    <p>Organo de contratacion: {{ $proyecto['organo_contratacion']}} </p>
                    <a href="{{ $proyecto['link_licitacion']}}">Link</a>
                    <br>
                    <p>Fecha de publicacion: {{ date('d-m-Y', strtotime($proyecto['fecha_publicacion'])) }}</p>
                    <br>
                    <a href=" {{ route ('proyecto_usuario.new') }}?id={{ urlencode($proyecto['id']) }} " class="btn btn-warning"> Iniciar proyecto nuevo</a>
                    <a href=" {{route ('proyectos.index')}}" class= "btn btn-warning">Volver al listado</a>

                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>