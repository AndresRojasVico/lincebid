<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    {{-- modulo maquetado --}}

    {{-- fin de modulo maquetado--}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(isset($message))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif

            {{-- BLOQUE DE INFORMACION DEL USUSARO --}}

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col items-center p-6">
                    <img src="{{ route('user.image', ['filename' => Auth::user()->image]) }}"
                        class="rounded-full h-20 w-20  mx-auto" />
                    <h3 class="text-2xl text-red-600 font-bold">Hola {{ Auth::user()->name }}</h3>
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('user.admin') }}">Zona de administrador</a>
                    @else
                        <h3>User</h3>
                    @endif
                    <br>
                    <a href="{{ route('proyectos.index') }}">Buscar proyectos nuevos</a>
                    <br>
                    <a href="{{ route('proyectosUrl')}}">Proyecto desde url aton de hacienda</a>
                </div>
                {{--listado de proyectos--}}
                <div class="col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-900 p-6">
                    <table class="table table-striped table-hover">
                     <thead>
                            <tr>
                                <th scope="col" class="columna-id-estrecha">Expediente</th>
                                <th scope="col">Organo de contratacion</th>
                                <th scope="col">Fecha de presentacion</th>
                                <th scope="col">Lugar</th>
                                <th scope="col">Objeto contrato</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($misProyectos as $entry)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        @if(auth()->user()->tieneProyecto($entry['id']))
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-check" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
                                        </svg>
                                        @endif
                                        {{$entry['expediente']}}
                                    </div>
                                </td>
                                <td>{{ $entry['organo_contratacion'] }}</td>
                                <td>{{date('d-m-Y', strtotime($entry['fecha_presentacion']))}}</td>
                                <td>{{ $entry['lugar_ejecucion'] }}</td>

                                <td>
                                    @php
                                    $objeto_contrato = $entry['objeto_contrato'];
                                    $limite = 100;
                                    $texto_limitado = $objeto_contrato;

                                    // Comprobar si el texto es más largo que 50 caracteres
                                    if (strlen($objeto_contrato) > $limite) {
                                    // Tomar los primeros 50 caracteres y añadir '...'
                                    $texto_limitado = substr($objeto_contrato, 0, $limite) . '...';
                                    }
                                    @endphp
                                    {{ $texto_limitado }}
                                </td>
                                <td>{{ $entry['presupuesto_sin_impuestos']}}</td>

                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        
                                        <a href="{{ route('proyecto.detalle') }}?id={{ urlencode($entry['id']) }}" class="btn btn-warning">Ver</a>

                                        
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                        
                </div>
                <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-900 p-6">
                    buscar proyectos en los que estoy participando
                </div>
                <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-900 p-6">
                    buscar proyectos en los que estoy participando
                </div>
                <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-900 p-6">
                    buscar proyectos en los que estoy participando
                </div>
            </div>

        </div>
    </div>
</x-app-layout>