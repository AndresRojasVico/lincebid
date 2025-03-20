<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class proyecto_usuario extends Model
{



    protected $table = 'proyectos_usuarios';

    //muchos a uno  con estados proyectos UN PROYECTO_USUARIOS PUEDE TENER SOLO UN ESTASO A LA VEZ y un estado_proyecto puede ser utilizado por mucho proyectos_usuarios 
    //relacion 1:1-1:M de uno a muchos
    public function estado_proyecto()
    {
        return $this->belongsTo('App\Models\estados_proyectos', 'estado_id');
    }

    //relacion proyectos_usuarios con  un proyectos
    //un proyecto_usuario solo opuede usar un proyecto ala vez y un proyecto puedes ser utilizado por mucho proyectos_usuarios
    //relacion: 1:1-1:M de muchos a uno lo
    public function proyecto()
    {
        return $this->belongsTo('App\Models\proyecto', 'proyecto_id');
    }

    //relecion de proyectos_usuarios con usuarios 
    //un proyecto_usuario ha sido creado por un usuario y un usuario puede crear muchos proyectos_usuarios
    //tipo de relacion: 1:1-1:M muchos a uno 
    public function usuario()
    {
        return $this->belongsTo('App\Models\User', 'usuario_id');
    }

    //relacion de proyectos_usuarios con tareas
    //un proyecto_usuario puede tener muchas tareas y una tarea pertenece a un proyecto_usuario
    //relacion 1:M-1:1  de uno a muchos
    public function tareas()
    {
        return $this->hasMany('App\Models\tarea');
    }
}
