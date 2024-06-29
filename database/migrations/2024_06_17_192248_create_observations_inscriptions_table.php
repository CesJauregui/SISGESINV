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
        Schema::create('observations_inscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inscription_id');
            $table->unsignedBigInteger('user_id');
            $table->string('description');
            $table->timestamps();
            $table->foreign('inscription_id')->references('id')->on('inscriptions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observations_inscriptions');
    }
};
