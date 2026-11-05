<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itens', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete();
            $table->foreignId('local_id')->constrained('locais')->restrictOnDelete();
            $table->date('data_achado');
            $table->date('data_entrega');
            $table->text('descricao')->nullable();
            $table->string('condicao', 100)->nullable();
            $table->string('status', 20)->default('disponivel');
            $table->string('foto', 255)->nullable();
            $table->string('entregue_por_nome', 150)->nullable();
            $table->string('entregue_por_vinculo', 20)->nullable();
            $table->foreignId('usuario_cadastro_id')->constrained('users')->restrictOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('data_achado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itens');
    }
};
