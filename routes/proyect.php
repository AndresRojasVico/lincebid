<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AtonController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProyectoUsuarioController;
use Illuminate\Support\Facades\Route;
use App\Models\proyecto_usuario;



//LISTAR
Route::get('/seachProyect', [ProjectController::class, 'index'])->middleware('auth')->name('proyectos.index');
//ELIMINAR
Route::get('/deleteProyect/{id?}', [ProjectController::class, 'delete'])->middleware('auth')->name('proyecto.delete');
//DETALLE
Route::get('/detailProyect/{id?}', [ProjectController::class, 'show'])->middleware('auth')->name('proyecto.detalle');


//PROYECTOS DE USUARIO
//LISTAR PROYECTOS ASIGNADOS A USUARIO
Route::get('/proyecto_usuario/index/{data?}', [ProyectoUsuarioController::class, 'index'])->middleware('auth')->name('proyecto_usuario.index');

//CREAR NUEVO PROYECTO USUARIO
Route::get('/proyecto_usuario/{id?}', [ProyectoUsuarioController::class, 'newProyecto'])->name('proyecto_usuario.new');

Route::get('/proyectos', [AtonController::class, 'loadConten'])->middleware('auth')->name('loadConten');
Route::get('/proyectosUrl', [AtonController::class, 'loadContenUrl'])->middleware('auth')->name('proyectosUrl');

Route::get('/proyectoUrl/{data}', function ($data) {
    return view('proyectos.proyectoUrl', ['data' => $data]);
})->name('proyectoUrl');
