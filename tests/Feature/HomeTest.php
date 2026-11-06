<?php

namespace Tests\Feature;

use App\Enums\PerfilUsuario;
use App\Enums\StatusItem;
use App\Models\Categoria;
use App\Models\Item;
use App\Models\Local;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    private function criarItem(string $nome, StatusItem $status, string $data = '2026-05-05'): Item
    {
        $servidor = User::firstOrCreate(
            ['email' => 'ddi@teste.local'],
            ['name' => 'Servidor', 'password' => 'password']
        );

        return Item::create([
            'nome' => $nome,
            'categoria_id' => Categoria::firstOrCreate(['nome' => 'Documentos'])->id,
            'local_id' => Local::firstOrCreate(['nome' => 'CCET'])->id,
            'data_achado' => $data,
            'data_entrega' => $data,
            'status' => $status,
            'usuario_cadastro_id' => $servidor->id,
        ]);
    }

    public function test_pagina_inicial_e_publica(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Reconectando o que')
            ->assertSee('Perdidos recentes')
            ->assertSee('Tem alguma dúvida?');
    }

    public function test_exibe_itens_disponiveis_com_data_e_local(): void
    {
        $this->criarItem('Carteira preta', StatusItem::Disponivel, '2026-05-05');

        $this->get('/')
            ->assertSee('Carteira preta')
            ->assertSee('05/05/26')
            ->assertSee('CCET');
    }

    public function test_nao_exibe_itens_devolvidos(): void
    {
        $this->criarItem('Mochila azul', StatusItem::Devolvido);

        $this->get('/')->assertDontSee('Mochila azul');
    }

    public function test_exibe_no_maximo_oito_itens(): void
    {
        foreach (range(1, 10) as $n) {
            $this->criarItem("Item numero {$n}", StatusItem::Disponivel, sprintf('2026-05-%02d', $n));
        }

        $this->get('/')
            ->assertSee('Item numero 10')
            ->assertSee('Item numero 3')
            ->assertDontSee('Item numero 2 -')
            ->assertDontSee('Item numero 1 -');
    }

    public function test_sem_itens_mostra_mensagem(): void
    {
        $this->get('/')->assertSee('Nenhum item cadastrado por enquanto.');
    }

    public function test_visitante_ve_entrar_e_autenticado_ve_minha_area(): void
    {
        $this->get('/')->assertSee('ENTRAR')->assertDontSee('MINHA ÁREA');

        $usuario = User::factory()->create(['tipo' => PerfilUsuario::Comum, 'ativo' => true]);

        $this->actingAs($usuario)->get('/')->assertSee('MINHA ÁREA')->assertDontSee('ENTRAR');
    }
}
