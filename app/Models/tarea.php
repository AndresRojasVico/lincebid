<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tarea extends Model
{
    //
    protected $table = 'tareas';

    //relacion de tareas con proyectos_usuarios
    //una tarea pertenece a un proyecto_usuario y un proyecto_usuario puede tener muchas tareas
    //relacion 1:1-1:M de muchos a uno 
    public function proyecto_usuario()
    {
        return $this->belongsTo('App\Models\proyecto_usuario', 'proyecto_usuario_id');
    }

    //relacion de tareas con estados_tareas
    //una tarea puede tener un estado_tarea y un estado_tarea puede ser utilizado por muchas tareas
    //relacion 1:1-1:M de muchos a uno
    public function estado_tarea()
    {
        return $this->belongsTo('App\Models\estado_tarea', 'estado_tarea_id');
    }

    //relacion de tareas con usuarios
    //una tarea puede ser creada por un usuario y un usuario puede crear muchas tareas
    //relacion 1:1-1:M de muchos a uno
    public function usuario()
    {
        return $this->belongsTo('App\Models\usuario', 'asignado_a_id');
    }
}
