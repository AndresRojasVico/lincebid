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
         CREATE TABLE estados_tareas(
             id INT AUTO_INCREMENT PRIMARY KEY,
             estado VARCHAR(100)
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
