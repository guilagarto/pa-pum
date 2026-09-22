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
    Schema::create('messages', function (Blueprint $table) {
        $table->id();
        // Vincula a conversa ao ID do atendimento/histórico gerado
        $table->foreignId('job_id')->constrained()->onDelete('cascade');
        // Quem enviou a mensagem
        $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
        // Quem vai receber a mensagem
        $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
        
        $table->text('message');
        $table->boolean('is_read')->default(false); // Para sabermos se a mensagem foi lida
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('messages');
}

};
