<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        DB::statement("
         CREATE TABLE tareas(
             id INT AUTO_INCREMENT PRIMARY KEY,
             tarea VARCHAR(255),
             fecha_inicio DATETIME,
             fecha_fin DATETIME,
             estado VARCHAR(100),
             usuario_id INT (11),
             proyecto_id INT (11),
             estado_tarea_id INT,
             FOREIGN KEY (usuario_id) REFERENCES users(id),
             FOREIGN KEY (proyecto_id) REFERENCES proyectos(id),
             FOREIGN KEY (estado_tarea_id) REFERENCES estados_tareas(id)
         );
        
         ");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
