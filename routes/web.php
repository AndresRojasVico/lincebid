<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AtonController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProyectoUsuarioController;
use Illuminate\Support\Facades\Route;
use App\Models\proyecto_usuario;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/seachProyect', [ProjectController::class, 'index'])->middleware('auth')->name('proyectos.index');
Route::get('/proyecto/delete/{id?}', [ProjectController::class, 'delete'])->middleware('auth')->name('proyecto.delete');

/*
Route::get('proyecto/delete/{id}', function($id){
     echo "ID a eliminar: " . $id; // Debugging line
     die(); // Stop execution to check the output
});
*/
Route::get('/proyecto/{id?}', [ProjectController::class, 'show'])->middleware('auth')->name('proyecto.detalle');


Route::get('/user/config', [UserController::class, 'config'])->middleware('auth')->name('user.config');
Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
Route::get('/user/image/{filename}', [UserController::class, 'getImage'])->name('user.image');

Route::get('/user/admin', function () {
    return view('user.useradmin');
})->middleware('auth', 'rol')->name('user.admin');


Route::get('/proyectos', [AtonController::class, 'loadConten'])->middleware('auth')->name('loadConten');
Route::get('/proyectosUrl', [AtonController::class, 'loadContenUrl'])->middleware('auth')->name('proyectosUrl');

Route::get('/proyectoUrl/{data}', function ($data) {
    return view('proyectos.proyectoUrl', ['data' => $data]);
})->name('proyectoUrl');


//proyecto_usuario

//Route::get('/proyecto_usuario/index', [ProyectoUsuarioController::class, 'index'])->name('proyecto_usuario.index');
Route::get('/proyecto_usuario/index/{data?}', [ProyectoUsuarioController::class, 'index'])->middleware('auth')->name('proyecto_usuario.index');

Route::get('/proyecto_usuario/{id?}', [ProyectoUsuarioController::class, 'newProyecto'])->name('proyecto_usuario.new');

Route::post('/aton/upload', [AtonController::class, 'upload'])->name('user.upload.post');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
