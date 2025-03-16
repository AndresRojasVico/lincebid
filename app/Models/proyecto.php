<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class proyecto extends Model
{
    //
    protected $table = 'proyectos';

    //relacion con estados estados proyectos  relacion de uno a muchos 
    //proque cada proyecto puede tener muchos estasdos a la vez pero un estado puede estar aplicado a muchos proyectos 

    public function estado()
    {
        return $this->belongsTo('App\Models\estado_proyecto', 'estado_id');
    }



    //relacion de proyectos con usuarios un usuario puede tener muchos proyectos y un proyecto puede ser gestionado por mucho usuarios
    // relacno de muchos a muchos M:N


    //ralacion con tareas



}
