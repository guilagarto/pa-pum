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
    Schema::table('jobs', function (Blueprint $table) {
        // O user_id original continua sendo o profissional que anunciou o serviço.
        // Agora adicionamos o contractor_id para registrar quem clicou no botão de chat (Contratante)
        $table->foreignId('contractor_id')->nullable()->constrained('users')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('jobs', function (Blueprint $table) {
        $table->dropForeign(['contractor_id']);
        $table->dropColumn('contractor_id');
    });
}

};
