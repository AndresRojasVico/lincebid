<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(empty($data))
                    <a href="{{ url('/aton/upload') }}">formulario</a>
                    <br>
                    <a href="{{ url('/aton/load') }}">Mostar contenido</a>
                    <br>
                    <a href="https://www.hacienda.gob.es/es-ES/GobiernoAbierto/Datos%20Abiertos/Paginas/LicitacionesContratante.aspx">Descargar licitaciones </a>
                    @else
                    <h3>fecha de actualizacion: {{$data[0]['fecha_update']}}</h3>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>

                                <th scope="col">Contador</th>
                                <th scope="col">Sumario</th>
                                <th scope="col">Titulo</th>
                                <th scope="col">ContractFolderStatus</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Fecha de publicacion</th>
                                <th scope="col">Codigos</th>
                                <th scope="col">Acciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $entry)
                            <tr>
                                <td>{{$entry['contador']}}</td>
                                <td>{{ $entry['summary'] }}</td>
                                <td><a href="{{ $entry['link'] }}" target="_blank">{{ $entry['title'] }}</a></td>
                                <td> {{$entry['ContractFolderID']}}</td>
                                <td> {{$entry['importe']}}€</td>
                                <td> {{$entry['fecha_publicacion']}}</td>
                                <td>
                                    @foreach($entry['codigos'] as $codigo)
                                    {{$codigo}}
                                    @endforeach

                                </td>
                                <td>
                                    <a href=" {{ route('proyectoUrl',['data'=>$entry['link']]) }}">Ver</a>

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