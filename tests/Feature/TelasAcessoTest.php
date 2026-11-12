<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TelasAcessoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()->setLocale('pt_BR');
    }

    public function test_tela_de_entrar_tem_link_para_criar_conta(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Entrar na conta')
            ->assertSee(route('register'), false)
            ->assertSee(route('password.request'), false);
    }

    public function test_tela_de_criar_conta_tem_link_para_entrar_e_consentimento(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Criar conta')
            ->assertSee('name="consentimento"', false)
            ->assertSee(route('login'), false);
    }

    public function test_cabecalho_da_home_leva_para_as_telas_de_acesso(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('login'), false)
            ->assertSee(route('register'), false);
    }

    public function test_tela_de_recuperar_senha_renderiza(): void
    {
        $this->get(route('password.request'))->assertOk()->assertSee('Recuperar senha');
    }

    public function test_erro_de_entrada_aparece_em_portugues(): void
    {
        $usuario = User::factory()->create();

        $this->from(route('login'))
            ->post(route('login'), ['email' => $usuario->email, 'password' => 'errada'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => 'E-mail ou senha incorretos. Confira os dados e tente de novo.']);
    }
}
