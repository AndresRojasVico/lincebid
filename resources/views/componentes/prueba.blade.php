<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Componente de Prueba
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Contenido del componente de prueba -->
                    <h2>esto son purevas de componentes</h2>
                    <x-danger-button>
                        Botón de Peligro
                    </x-danger-button>
                </div>
            </div>
        </div>
</x-app-layout>