<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //

        DB::statement("
            CREATE TABLE estados_proyectos_usuarios(
            id INT (11) AUTO_INCREMENT,
            descripcion_estado VARCHAR(255),
            CONSTRAINT id PRIMARY KEY (id)
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
