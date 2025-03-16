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
          CREATE TABLE proyectos_usuarios(
            id INT (11) AUTO_INCREMENT,
            usuario_id INT (11),
            proyecto_id INT (11),
            rol VARCHAR(100),
            estado_id INT (11),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT pk_proyectos_usuarios PRIMARY KEY (id),
            CONSTRAINT fk_proyectos_usuarios_usuarios_id FOREIGN KEY (usuario_id) REFERENCES users(id),
            CONSTRAINT fk_proyectos_usuarios_proyectos_id FOREIGN KEY (proyecto_id) REFERENCES proyectos(id),
            CONSTRAINT fk_proyectos_usuarios_estados_id FOREIGN KEY (estado_id) REFERENCES estados_proyectos_usuarios(id)
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
