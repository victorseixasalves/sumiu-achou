<?php

namespace Tests\Feature\Auth;

use App\Enums\PerfilUsuario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function dadosValidos(array $extras = []): array
    {
        return array_merge([
            'name' => 'Usuário de Teste',
            'email' => 'teste@aluno.edu.br',
            'telefone' => '(38) 99999-9999',
            'password' => 'password',
            'password_confirmation' => 'password',
            'consentimento' => '1',
        ], $extras);
    }

    public function test_tela_de_cadastro_pode_ser_exibida(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_novos_usuarios_podem_se_cadastrar(): void
    {
        $response = $this->post('/register', $this->dadosValidos());

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_cadastro_grava_perfil_comum_telefone_e_consentimento(): void
    {
        $this->post('/register', $this->dadosValidos());

        $usuario = User::where('email', 'teste@aluno.edu.br')->firstOrFail();

        $this->assertSame(PerfilUsuario::Comum, $usuario->tipo);
        $this->assertTrue($usuario->ativo);
        $this->assertSame('(38) 99999-9999', $usuario->telefone);
        $this->assertNotNull($usuario->consentimento_em);
    }

    public function test_cadastro_sem_consentimento_e_recusado(): void
    {
        $response = $this->post('/register', $this->dadosValidos(['consentimento' => null]));

        $response->assertSessionHasErrors('consentimento');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'teste@aluno.edu.br']);
    }

    public function test_perfil_nao_pode_ser_definido_pelo_formulario(): void
    {
        $this->post('/register', $this->dadosValidos(['tipo' => 'administrador']));

        $usuario = User::where('email', 'teste@aluno.edu.br')->firstOrFail();

        $this->assertSame(PerfilUsuario::Comum, $usuario->tipo);
    }

    public function test_telefone_e_opcional(): void
    {
        $this->post('/register', $this->dadosValidos(['telefone' => null]));

        $this->assertAuthenticated();
    }
}
