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
        Schema::table('proyectos_usuarios', function (Blueprint $table) {
            $table->foreign(['proyecto_id'], 'fk_proyectos_id')->references(['id'])->on('proyectos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['estado_id'], 'fk_proyectos_usuarios_estados_id')->references(['id'])->on('estados_proyectos_usuarios')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['usuario_id'], 'fk_proyectos_usuarios_usuarios_id')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyectos_usuarios', function (Blueprint $table) {
            $table->dropForeign('fk_proyectos_id');
            $table->dropForeign('fk_proyectos_usuarios_estados_id');
            $table->dropForeign('fk_proyectos_usuarios_usuarios_id');
        });
    }
};
