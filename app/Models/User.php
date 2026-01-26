<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'rol',
        'surname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    //relacion de usuarios con proyectos_usuarios
    //un usuario puede tener muchos proyectos_usuarios y un proyecto_usuario solo puede ser utilizado por un usuario
    //relacion 1:M-1:1 de uno a muchos
    public function proyectos_usuarios()
    {
        //return $this->hasMany('App\Models\proyecto_usuario');
        return $this->hasMany(Proyecto_usuario::class, 'usuario_id'); // Asegúrate de usar la columna correcta
    }

    //relacion de usuarios con tareas
    //un usuario puede tener muchas tareas y una tarea puede ser creada por un usuario
    //relacion 1:M-1:1 de uno a muchos
    public function tareas()
    {
        return $this->hasMany('App\Models\tarea');
    }

    // Método para verificar si el usuario es administrador
    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }


    // En User.php
    public function tieneProyecto($proyectoId)
    {
        return $this->proyectos_usuarios->contains('proyecto_id', $proyectoId);
    }
}
