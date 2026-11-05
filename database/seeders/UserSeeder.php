<?php

namespace Database\Seeders;

use App\Enums\PerfilUsuario;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Usuários APENAS para desenvolvimento e testes (senha: password).
 * Não executar este seeder em ambiente de produção.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            ['name' => 'Administrador Teste', 'email' => 'admin@teste.local', 'tipo' => PerfilUsuario::Administrador],
            ['name' => 'Servidor DDI Teste', 'email' => 'ddi@teste.local', 'tipo' => PerfilUsuario::ServidorDdi],
            ['name' => 'Aluno Teste', 'email' => 'aluno@teste.local', 'tipo' => PerfilUsuario::Comum],
        ];

        foreach ($usuarios as $dados) {
            $usuario = User::firstOrNew(['email' => $dados['email']]);
            $usuario->name = $dados['name'];
            $usuario->tipo = $dados['tipo'];
            $usuario->password = Hash::make('password');
            $usuario->email_verified_at = now();
            $usuario->consentimento_em = now();
            $usuario->ativo = true;
            $usuario->save();
        }
    }
}
