<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class proyecto extends Model
{
    //
    protected $table = 'proyectos';
    public $timestamps = false;

    //relacion proyectos con proyectos_usuarios
    //un proyecto puede se utilizado por muchos proyectos_usuarios y un proyecto_usuario solo puede utilizar un proyecto a la vez
    //tipo de realion: 1:M - 1:1  de uno a muchos  
    public function proyecto_usuario()
    {
        return $this->hasMany('App\Models\proyecto_usuario');
    }
}
