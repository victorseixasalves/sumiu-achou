<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devolucoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('itens')->restrictOnDelete();
            $table->foreignId('solicitante_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('aprovador_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->dateTime('data_solicitacao');
            $table->text('comprovacao_propriedade');
            $table->dateTime('data_decisao')->nullable();
            $table->string('motivo_rejeicao', 255)->nullable();
            $table->dateTime('data_efetivacao')->nullable();
            $table->string('status', 20)->default('solicitada');
            $table->string('numero_termo', 30)->nullable()->unique();
            $table->string('termo_arquivo', 255)->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devolucoes');
    }
};
