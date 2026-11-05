<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telefone', 20)->nullable()->after('email');
            $table->string('tipo', 20)->default('comum')->after('password');
            $table->boolean('ativo')->default(true)->after('tipo');
            // Obrigatório no cadastro (RF32): validado na aplicação.
            $table->dateTime('consentimento_em')->nullable()->after('ativo');
            $table->dateTime('anonimizado_em')->nullable()->after('consentimento_em');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telefone', 'tipo', 'ativo', 'consentimento_em', 'anonimizado_em']);
        });
    }
};
