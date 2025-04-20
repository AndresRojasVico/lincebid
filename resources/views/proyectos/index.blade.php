<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buscar proyectos</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h2>Proyectos</h2>


                    @if(empty($proyectos))
                    <p>No hay proyectos disponibles.</p>
                    @else


                    Ultima actualizacion: <br>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>

                                <th scope="col">id</th>
                                <th scope="col">fecha de actualizacion </th>
                                <th scope="col">Sumario</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Acciones</th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyectos as $entry)
                            <tr>
                                <td>{{ $entry['id'] }}</td>
                                <td>{{ $entry['fecha_actualizacion'] }}</td>
                                <td>{{ $entry['summary'] }}</td>
                                <td>{{ $entry['presupuesto_sin_impuestos']}}</td>
                                <td>
                                    <a href=" {{ route('proyecto.detalle', ['id'=> $entry['id']]) }} " type="button" class="btn btn-danger">ver</a>
                                    <button type="button" class="btn btn-danger">Eliminar</button>
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