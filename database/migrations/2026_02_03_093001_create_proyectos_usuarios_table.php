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
        Schema::create('proyectos_usuarios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre', 150);
            $table->integer('usuario_id')->nullable()->index('fk_proyectos_usuarios_usuarios_id');
            $table->integer('proyecto_id')->nullable()->index('fk_proyectos_id');
            $table->string('rol', 100)->nullable();
            $table->integer('estado_id')->nullable()->index('fk_proyectos_usuarios_estados_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos_usuarios');
    }
};
