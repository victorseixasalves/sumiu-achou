<?php

namespace Tests\Feature;

use App\Enums\PerfilUsuario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testes do controle de acesso por perfis (RF04, RF05, RF06).
 */
class ControleAcessoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function usuario(PerfilUsuario $tipo, bool $ativo = true): User
    {
        return User::factory()->create(['tipo' => $tipo, 'ativo' => $ativo]);
    }

    public function test_visitante_e_redirecionado_ao_login(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
        $this->get('/ddi')->assertRedirect(route('login'));
    }

    public function test_usuario_comum_nao_acessa_areas_restritas(): void
    {
        $comum = $this->usuario(PerfilUsuario::Comum);

        $this->actingAs($comum)->get('/admin')->assertForbidden();
        $this->actingAs($comum)->get('/ddi')->assertForbidden();
    }

    public function test_servidor_ddi_acessa_area_da_ddi_mas_nao_a_do_administrador(): void
    {
        $servidor = $this->usuario(PerfilUsuario::ServidorDdi);

        $this->actingAs($servidor)->get('/ddi')->assertOk();
        $this->actingAs($servidor)->get('/admin')->assertForbidden();
    }

    public function test_administrador_acessa_todas_as_areas(): void
    {
        $admin = $this->usuario(PerfilUsuario::Administrador);

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/ddi')->assertOk();
    }

    public function test_conta_desativada_perde_a_sessao(): void
    {
        $inativo = $this->usuario(PerfilUsuario::Comum, ativo: false);

        $this->actingAs($inativo)
            ->get('/dashboard')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
