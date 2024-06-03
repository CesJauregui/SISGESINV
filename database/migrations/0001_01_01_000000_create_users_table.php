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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('rol'); //es necesario para estudiante/egresado, UDI y docente
            $table->string('nombre'); //es necesario para estudiante/egresado, UDI y docente
            $table->string('apellidos'); //es necesario para estudiante/egresado, UDI y docente
            $table->string('email')->unique(); //es necesario para estudiante/egresado, UDI y docente
            $table->string('password'); //es necesario para estudiante/egresado, UDI y docente
            $table->integer('celular'); //es necesario para estudiante/egresado, UDI y docente
            $table->integer('codigo'); //es necesario para estudiante/egresado, UDI y docente
            $table->timestamp('fecha_egreso')->nullable(); //es necesario para estudiante/egresado
            $table->string('carrera')->nullable(); //es necesario para estudiante/egresado y docente
            $table->string('linea')->nullable(); //es necesario para docente
            $table->string('sub_lineas')->nullable(); //es necesario para docente
            $table->boolean('es_revisor')->default(false); //es necesario para docente
            $table->boolean('es_asesor')->default(false); //es necesario para docente
            $table->boolean('es_jurado')->default(false); //es necesario para docente
            $table->string('estado')->default('habilitado');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
