<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AtonController;
use Illuminate\Support\Facades\Route;
use App\Models\proyecto_usuario;


Route::get('/', function () {
    /*  
    $proyectos_usuarios = proyecto_usuario::all();
    foreach ($proyectos_usuarios as $proyecto_usuario) {
        echo  "nombre de proyecto: " .  $proyecto_usuario->nombre . '<br>';
        echo "el usuario que lo ha creado es :" . $proyecto_usuario->usuario->name . '<br>';
        echo "Numero de espediete:" . $proyecto_usuario->proyecto->numero_expediente . '<br>';
        echo "Tateas pendientes : <br>";
        foreach ($proyecto_usuario->tareas as $tarea) {
            //  var_dump($tarea->usuario);
            echo $tarea->nombre . "-" . "asignada a : " . $tarea->usuario->name . "/ el estado de la tarea es :" . $tarea->estado_tarea->estado .  ',<br> ';
        }
        echo '<hr>';
    }
    die();
    */
    return view('welcome');
});


Route::get('/user/config', [UserController::class, 'config'])->middleware('auth')->name('user.config');
Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
Route::get('/user/image/{filename}', [UserController::class, 'getImage'])->name('user.image');


Route::get('/proyectos', [AtonController::class, 'loadConten'])->middleware('auth')->name('proyectos');
Route::get('/proyectosUrl', [AtonController::class, 'loadContenUrl'])->middleware('auth')->name('proyectosUrl');

Route::get('/proyectoUrl/{data}', function ($data) {
    $data = json_decode($data, true);
    var_dump($data);
    die();
    return view('proyectos.proyectoUrl', ['data' => $data]);
})->name('proyectoUrl');


Route::get('/user/admin', function () {
    return view('user.useradmin');
})->middleware('auth', 'rol')->name('user.admin');

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
