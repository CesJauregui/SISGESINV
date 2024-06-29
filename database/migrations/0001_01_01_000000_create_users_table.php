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
            $table->string('role'); //es necesario para estudiante/egresado, UDI y docente
            $table->string('name'); //es necesario para estudiante/egresado, UDI y docente
            $table->string('surnames'); //es necesario para estudiante/egresado, UDI y docente
            $table->string('email')->unique(); //es necesario para estudiante/egresado, UDI y docente
            $table->string('password'); //es necesario para estudiante/egresado, UDI y docente
            $table->integer('phone'); //es necesario para estudiante/egresado, UDI y docente
            $table->integer('code'); //es necesario para estudiante/egresado, UDI y docente
            $table->date('discharge_date')->nullable(); //es necesario para estudiante/egresado
            $table->string('cycle')->nullable(); //es necesario para estudiante
            $table->string('career')->nullable(); //es necesario para estudiante/egresado y docente
            $table->string('line')->nullable(); //es necesario para docente
            $table->string('sublines')->nullable(); //es necesario para docente
            $table->boolean('is_reviewer')->default(false); //es necesario para docente
            $table->boolean('is_advisor')->default(false); //es necesario para docente
            $table->boolean('is_jury')->default(false); //es necesario para docente
            $table->string('orcid')->nullable();
            $table->integer('cip')->nullable();
            $table->string('status')->default('Habilitado');
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
