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
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        // Vincula ao anúncio de serviço prestado
        $table->foreignId('job_id')->constrained()->onDelete('cascade');
        // Registra quem está dando a nota (Contratante)
        $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
        // Registra quem está recebendo a nota (Prestador/Perfil)
        $table->foreignId('profile_id')->constrained('profiles')->onDelete('cascade');
        // Nota estrita de 1 a 5 estrelas
        $table->unsignedTinyInteger('rating');
        $table->text('comment')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('reviews');
}

};
