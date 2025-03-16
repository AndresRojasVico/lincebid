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
             id INT (11) AUTO_INCREMENT ,
             nombre VARCHAR(255),
             descripcion TEXT,
             proyecto_usuario_id INT (11),
             estado_id INT (11),
             created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
             updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
             asignado_a_id INT (11),
             CONSTRAINT pk_tarea PRIMARY KEY (id),
             CONSTRAINT fk_proyecto_usuario_id FOREIGN KEY (proyecto_usuario_id) REFERENCES proyectos_usuarios(id),
             CONSTRAINT fk_tarea_id FOREIGN KEY (estado_id) REFERENCES estados_tareas(id),
             CONSTRAINT fk_asignado_a_id FOREIGN KEY (asignado_a_id) REFERENCES users(id)
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
