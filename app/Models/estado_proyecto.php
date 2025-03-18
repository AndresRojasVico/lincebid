<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class estado_proyecto extends Model
{
    //
    protected $table = 'estados_proyectos_usuarios';
    // relacion_ un estado_proyecto_usuario  puede ser utilizado por muchos proyectos_usuarios y un proyecto_usuario solo puede tener un estado_proyecto a la vez 
    //relaciones proyectos_usuarios relacion de uno a mucho hasMany

    public function proyectos_usuarios()
    {
        return $this->hasMany('App\Models\proyectos_usuarios');
    }
}
