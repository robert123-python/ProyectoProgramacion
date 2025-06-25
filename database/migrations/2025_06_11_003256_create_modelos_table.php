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
        Schema::create('modelos', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->unsignedBigInteger('user_id'); // Clave foránea al usuario
            $table->string('nombre'); // Nombre que el usuario da al modelo
            $table->string('ruta_modelo'); // Ruta del archivo (ej: .h5, .pt, .xml)
            $table->timestamps(); // created_at y updated_at

            // Relación con la tabla users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelos');
    }
};
