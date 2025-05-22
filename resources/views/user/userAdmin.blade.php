<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Zona de administrador</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    Hola {{ Auth::user()->name }}, bienvenido a la zona de administrador de LinceBid. Aquí puedes gestionar los usuarios y las ofertas de la plataforma.
                    <br><br>

                    <a href="https://www.hacienda.gob.es/es-ES/GobiernoAbierto/Datos%20Abiertos/Paginas/LicitacionesContratante.aspx" target="_blank">Ministerio de acienda</a>

                    <br><br>
                    <!-- filepath: /resources/views/atom/upload.blade.php -->
                    <form action="{{ route('user.upload.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="atom_file" required>
                        <button class="btn btn-success" type="submit">Subir archivo</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>