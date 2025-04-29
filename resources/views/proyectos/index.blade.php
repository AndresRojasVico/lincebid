<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buscar proyectos</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    <h2>Proyectos</h2>


                    @if($proyectos->isEmpty())
                    <p>No hay proyectos disponibles.</p>
                    <br>
                    Es necesario actualizar la base de datos, por favor, dirijase a la seccion de administracion y actualice la base de datos. <br>
                    <a href="{{ route('user.admin') }}">Zona de administrador</a>
                    @else


                    Ultima actualizacion:{{ $proyectos[0]->fecha_actualizacion }} <br>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Organo de contratacion</th>
                                <th scope="col">Fecha de publicacio</th>
                                <th scope="col">Sumario</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Acciones</th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyectos as $entry)
                            <tr>
                                <td>{{ $entry['organo_contratacion'] }}</td>
                                <td>{{date('d-m-Y', strtotime($entry['fecha_publicacion']))}}</td>
                                <td>{{ $entry['summary'] }}</td>
                                <td>{{ $entry['presupuesto_sin_impuestos']}}</td>
                                <td>
                                    <a href="{{ route('proyecto.detalle') }}?id={{ urlencode($entry['id']) }}" class="btn btn-warning">Ver</a>

                                </td>
                            </tr>


                            @endforeach
                        </tbody>
                    </table>

                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>