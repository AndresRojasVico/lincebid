<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class estado_tarea extends Model
{
    //
    protected $table = 'estados_tareas';

    //relacion de estados_tareas con tareas
    //un estado_tarea puede ser utilizado por muchas tareas y una tarea puede tener un estado_tarea a la vez
    //relacion 1:M-1:1 de uno a muchos
    public function tareas()
    {
        return $this->hasMany('App\Models\tarea');
    }
}
