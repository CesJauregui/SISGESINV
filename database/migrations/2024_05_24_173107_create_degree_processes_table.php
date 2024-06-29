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
        Schema::create('degree_processes', function (Blueprint $table) {
            $table->id();
            $table->integer('file')->comment('expediente');
            $table->string('professional_school');
            $table->string('thesis_project_title');
            $table->string('office_number');
            $table->string('resolution_number');
            $table->string('general_status')->default('Inscripción');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('degree_processes');
    }
};
