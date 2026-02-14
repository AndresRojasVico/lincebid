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

            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                 
                <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col items-center p-3 ">
                    <img src="{{ route('user.image', ['filename' => Auth::user()->image]) }}"
                        class="rounded-full h-20 w-20  mx-auto" />
                    <h3 class="text-2xl text-red-600 font-bold">Hola {{ Auth::user()->name }}</h3>
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('user.admin') }}">Zona de administrador</a>
                    @else
                        <h3>User</h3>
                    @endif
                    
                    <a href="{{ route('proyectos.index') }}">Buscar proyectos nuevos</a>
                    
                    <a href="{{ route('proyectosUrl')}}">Proyecto desde url aton de hacienda</a>
                </div>
                {{--listado de proyectos--}}
                <div class="col-span-4 bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-900 p-6">
                    <table class="table table-striped table-hover">
                     <thead>
                            <tr>
                                <th scope="col" class="columna-id-estrecha">Expediente</th>
                                <th scope="col">Organo de contratacion</th>
                                <th scope="col">Fecha de presentacion</th>
                                
                                
                                <th scope="col">Importe</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        @foreach($misProyectos as $entry)
                            <tr> 
                                {{--expediente--}}
                                <td>{{$entry->proyecto->expediente}}</td>
                                {{--organo de contratacion--}}
                                <td>{{ $entry->proyecto->organo_contratacion}}</td>

                                <td>{{date('d-m-Y', strtotime($entry->proyecto->fecha_presentacion))}}</td>
                                

                                
                                {{--importe--}}
                                <td>{{ $entry->proyecto->presupuesto_sin_impuestos}}</td>

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