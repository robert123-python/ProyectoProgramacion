<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('modelo_imagenes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('modelo_id')->constrained('modelos')->onDelete('cascade');
        $table->string('ruta_imagen');
        $table->string('clase'); // por ejemplo: "perro", "gato", etc.
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelo_imagenes');
    }
};
