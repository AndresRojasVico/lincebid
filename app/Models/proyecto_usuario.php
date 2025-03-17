<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class proyecto_usuario extends Model
{
    protected $table = 'proyectos_usuarios';

    //relacion hasManyo  uno a muchos con estados proyectos
    public function estado_proyecto()
    {
        return $this->hasMany('App\Models\estados_proyectos');
    }

    //relacion proyectos_usuarios con  un proyectos :un proyecto puede ser utilizado por muchos proyectos_usuariso y proyecto_usuario puede solo puede utilizar un proyecto 
    //relacion de uno a mucho hasMany
    public function proyecto()
    {
        return $this->belongsTo('App\Models\proyecto');
    }

    //relecion de proyectos_usuarios con usuarios 

}
