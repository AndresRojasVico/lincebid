<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">Detalles del proyecto </h>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(empty($proyecto))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <p class="text-lg text-gray-500">No hay información del proyecto disponible.</p>
                        <a href="{{ route('proyectos.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Volver al listado
                        </a>
                    </div>
                </div>
            @else

            <!-- Main Info Card -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
<div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex flex-wrap items-baseline gap-4">
    <h3 class="text-2xl font-bold text-gray-900">
        {{ $proyecto['organo_contratacion'] }}
    </h3>
    <p class="text-sm text-gray-500 uppercase tracking-wide font-semibold">
        Expediente: <span class="text-gray-700">{{ $proyecto['expediente'] }}</span>
    </p>
</div>

    <div class="p-6">
        <div class="md:flex md:justify-between md:items-start">
            <div class="md:w-3/4">
               
                <h2 class="text-lg font-bold text-gray-800"">{{ $proyecto['objeto_contrato'] }}</h2>
            </div>
        </div>
    </div>
</div>


             

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Fechas Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Fechas Clave</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div>
                                    <p class="text-sm text-gray-500">Fecha Publicación</p>
                                    <p class="font-medium text-gray-900">{{ date('d-m-Y', strtotime($proyecto['fecha_publicacion'])) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <p class="text-sm text-gray-500">Fecha Máxima Presentación</p>
                                    <p class="font-medium text-red-600">{{ date('d-m-Y', strtotime($proyecto['fecha_presentacion'])) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Economico Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                    <div class="p-6 flex flex-col justify-between h-full">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Datos Económicos</h3>
                            <p class="text-sm text-gray-500 mb-1">Presupuesto base sin impuestos</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $proyecto['presupuesto_sin_impuestos'] }} €</p>
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ $proyecto['link_licitacion'] }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                Ver licitación oficial
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('proyectos.index') }}" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Volver al listado
                </a>
                <a href="{{ route('proyecto_usuario.new') }}" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    iniciar proyecto
                </a>
                
            </div>

            @endif
        </div>
    </div>
</x-app-layout>