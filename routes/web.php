<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AtonController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProyectoUsuarioController;
use Illuminate\Support\Facades\Route;
use App\Models\proyecto_usuario;

/*
Route::get('/', function () {
    return view('welcome');
});
*/
//redirect to dashboard
Route::redirect('/', '/dashboard');

Route::get('/user/config', [UserController::class, 'config'])->middleware('auth')->name('user.config');
Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
Route::get('/user/image/{filename}', [UserController::class, 'getImage'])->name('user.image');

Route::get('/user/admin', function () {
    return view('user.useradmin');
})->middleware('auth', 'rol')->name('user.admin');

/*
Route::get('/proyectos', [AtonController::class, 'loadConten'])->middleware('auth')->name('loadConten');
Route::get('/proyectosUrl', [AtonController::class, 'loadContenUrl'])->middleware('auth')->name('proyectosUrl');

Route::get('/proyectoUrl/{data}', function ($data) {
    return view('proyectos.proyectoUrl', ['data' => $data]);
})->name('proyectoUrl');
*/






Route::post('/aton/upload', [AtonController::class, 'upload'])->name('user.upload.post');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/dashboard', [ProyectoUsuarioController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/componentes/prueba', function () {
    return view('componentes.prueba');
})->name('componentes.prueba');

require __DIR__ . '/auth.php';
