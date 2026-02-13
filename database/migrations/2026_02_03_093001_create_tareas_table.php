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
        Schema::create('tareas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre')->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('proyecto_usuario_id')->nullable()->index('fk_proyecto_usuario_id');
            $table->integer('estado_id')->nullable()->index('fk_tarea_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->integer('asignado_a_id')->nullable()->index('fk_asignado_a_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
