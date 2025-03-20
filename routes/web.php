<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\proyecto_usuario;


Route::get('/', function () {
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
