<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class estado_proyecto extends Model
{
    //
    protected $table = 'estados_proyectos_usuarios';

    //relaciones proyectos_usuarios relacion de uno a mucho hasMany

    public function proyectos_usuarios()
    {
        return $this->hasMany('App\Models\proyectos_usuarios');
    }
}
