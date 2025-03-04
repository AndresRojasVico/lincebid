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
           CREATE TABLE proyectos(
           id INT AUTO_INCREMENT PRIMARY KEY,
           link_licitacion VARCHAR(255),
           fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           vigente_anulada_archivada VARCHAR(100),
           primera_publicacion DATETIME,
           estado VARCHAR(100),
           numero_expediente VARCHAR(100),
           objeto_contrato VARCHAR(255),
           presupuesto_sin_impuestos DECIMAL(15,2),
           presupuesto_con_impuestos DECIMAL(15,2),
           cpv VARCHAR(50),
           tipo_contrato VARCHAR(100),
           lugar_ejecucion VARCHAR(255),
           organo_contratacion VARCHAR(255),
           id_oc_picasp VARCHAR(100),
           nif_oc VARCHAR(100),
           enlace_perfil_contratante TEXT,
           tipo_administracion VARCHAR(255),
           codigo_postal VARCHAR(20),
           tipo_procedimiento VARCHAR(100),
           sistema_contratacion VARCHAR(100),
           tramitacion VARCHAR(100),
           forma_presentacion VARCHAR(100),
           fecha_presentacion DATETIME,
           fecha_solicitudes DATETIME,
           directiva_aplicacion VARCHAR(100),
           financiacion_europea VARCHAR(100),
           descripcion_financiacion TEXT,
           subcontratacion_permitido BOOLEAN,
           subcontratacion_porcentaje DECIMAL(5,2),
           estado_nuestro_id INT,
           usuario_id INT,
           notas TEXT
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
