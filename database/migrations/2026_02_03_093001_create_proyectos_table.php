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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('expediente');
            $table->string('link_licitacion')->nullable();
            $table->text('summary')->nullable();
            $table->timestamp('fecha_actualizacion')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->string('vigente_anulada_archivada', 100)->nullable();
            $table->dateTime('primera_publicacion')->nullable();
            $table->string('estado', 100)->nullable();
            $table->string('numero_expediente', 100)->nullable();
            $table->mediumText('objeto_contrato')->nullable();
            $table->decimal('presupuesto_sin_impuestos', 15)->nullable();
            $table->decimal('presupuesto_con_impuestos', 15)->nullable();
            $table->string('cpv', 50)->nullable();
            $table->string('tipo_contrato', 100)->nullable();
            $table->string('lugar_ejecucion')->nullable();
            $table->string('organo_contratacion')->nullable();
            $table->string('id_oc_picasp', 100)->nullable();
            $table->string('nif_oc', 100)->nullable();
            $table->text('enlace_perfil_contratante')->nullable();
            $table->string('tipo_administracion')->nullable();
            $table->string('codigo_postal', 20)->nullable();
            $table->string('tipo_procedimiento', 100)->nullable();
            $table->string('sistema_contratacion', 100)->nullable();
            $table->string('tramitacion', 100)->nullable();
            $table->string('forma_presentacion', 100)->nullable();
            $table->dateTime('fecha_presentacion')->nullable();
            $table->dateTime('fecha_solicitudes')->nullable();
            $table->timestamp('fecha_publicacion')->nullable();
            $table->string('directiva_aplicacion', 100)->nullable();
            $table->string('financiacion_europea', 100)->nullable();
            $table->text('descripcion_financiacion')->nullable();
            $table->boolean('subcontratacion_permitido')->nullable();
            $table->decimal('subcontratacion_porcentaje', 5)->nullable();
            $table->integer('estado_nuestro_id')->nullable();
            $table->integer('usuario_id')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
