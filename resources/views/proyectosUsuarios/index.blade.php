<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis proyectos</h2>
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

                    @if($misProyectos->isEmpty())
                    <p>No hay proyectos disponibles.</p>
                    <br>
                    @else



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
                            @foreach($misProyectos as $miProyecto)
                            <tr>
                                <td>{{ $miProyecto->proyecto->id}}</td>
                                <td>{{date('d-m-Y', strtotime($miProyecto->proyecto->fecha_publicacion ))}}</td>
                                <td>{{ $miProyecto->proyecto->summary}}</td>
                                <td>{{ $miProyecto->proyecto->presupuesto_sin_impuestos}}</td>
                                <td>
                                    <a href=" {{$miProyecto->proyecto->link_licitacion}}" class=" btn btn-primary">Ver</a>
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