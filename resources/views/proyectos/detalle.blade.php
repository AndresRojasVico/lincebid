@if(empty($proyecto))
<p>No hay proyectos disponibles.</p>
@else
<h2>Detalles del proyecto</h2>
Ultima actualizacion: {{ $proyecto['id'] }} <br>
@endif