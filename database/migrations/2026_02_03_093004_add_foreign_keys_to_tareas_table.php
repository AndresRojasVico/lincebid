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
        Schema::table('tareas', function (Blueprint $table) {
            $table->foreign(['asignado_a_id'], 'fk_asignado_a_id')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['proyecto_usuario_id'], 'fk_proyecto_usuario_id')->references(['id'])->on('proyectos_usuarios')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['estado_id'], 'fk_tarea_id')->references(['id'])->on('estados_tareas')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropForeign('fk_asignado_a_id');
            $table->dropForeign('fk_proyecto_usuario_id');
            $table->dropForeign('fk_tarea_id');
        });
    }
};
