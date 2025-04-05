<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buscar proyectos</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h2>Listado de proyecto para buscar</h2>
                    @if(empty($data))
                    <p>No hay proyectos disponibles para buscar.</p>
                    @else
                    <h3>Fecha de actualizacion: {{$data[0]['fecha_update']}}</h3>

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>

                                <th scope="col">Contador</th>
                                <th scope="col">Sumario</th>
                                <th scope="col">Titulo</th>
                                <th scope="col">ContractFolderStatus</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Codigos</th>
                                <th scope="col">Acciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $entry)
                            <tr>
                                <td>{{ $entry['contador'] }}</td>
                                <td>{{ $entry['summary'] }}</td>
                                <td><a href="{{ $entry['link'] }}">{{ $entry['title'] }}</a></td>
                                <td> {{$entry['ContractFolderID']}}</td>
                                <td> {{$entry['importe']}}€</td>
                                <td>
                                    @foreach($entry['codigos'] as $codigo)
                                    {{$codigo}}
                                    @endforeach

                                </td>
                                <td><button type="button" class="btn btn-success">Ver</button> <button type="button" class="btn btn-danger">Eliminar</button></td>
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