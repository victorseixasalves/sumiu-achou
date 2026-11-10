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
            ->assertSee('Como funciona')
            ->assertSee('Tem alguma')
            ->assertSee('dúvida?');
    }

    public function test_exibe_itens_disponiveis_com_data_e_local(): void
    {
        $this->criarItem('Carteira preta', StatusItem::Disponivel, '2026-05-05');

        $this->get('/')
            ->assertSee('Carteira preta')
            ->assertSee('05/05/26')
            ->assertSee('CCET');
    }

    public function test_nao_exibe_itens_devolvidos_no_carrossel(): void
    {
        $this->criarItem('Mochila azul', StatusItem::Devolvido);

        $this->get('/')->assertDontSee('Mochila azul');
    }

    public function test_exibe_no_maximo_dez_itens(): void
    {
        foreach (range(1, 12) as $n) {
            $this->criarItem("Item numero {$n}", StatusItem::Disponivel, sprintf('2026-05-%02d', $n));
        }

        $this->get('/')
            ->assertSee('>Item numero 12</h3>', false)
            ->assertSee('>Item numero 3</h3>', false)
            ->assertDontSee('>Item numero 2</h3>', false)
            ->assertDontSee('>Item numero 1</h3>', false);
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

    public function test_exibe_perguntas_frequentes_e_a_janela_de_resposta(): void
    {
        $this->get('/')
            ->assertSee('Onde ficam os itens perdidos?')
            ->assertSee('Achei um item. Onde devo entregar?')
            ->assertSee('Quer ver o passo a passo completo?')
            ->assertSee('role="dialog"', false);
    }

    public function test_exibe_os_tres_passos_de_como_funciona(): void
    {
        $this->get('/')
            ->assertSee('Como funciona')
            ->assertSee('Passo 1')
            ->assertSee('Passo 2')
            ->assertSee('Passo 3')
            ->assertDontSee('Passo 4')
            ->assertSee('Achou algo? Leve até a DDI')
            ->assertSee('Procure no sistema')
            ->assertSee('Solicite a devolução');
    }

    public function test_usa_o_logo_e_as_fotos_enviadas(): void
    {
        $this->get('/')
            ->assertSee('images/logo-sumiu-achou.png', false)
            ->assertSee('images/home/estudantes-hero.webp', false)
            ->assertSee('images/home/estudante-duvidas.webp', false);
    }

    public function test_hero_tem_botoes_de_acao(): void
    {
        $this->get('/')
            ->assertSee('Procurar um item')
            ->assertSee('href="#como-funciona"', false);
    }

    public function test_busca_em_destaque_com_buscas_rapidas_por_categoria(): void
    {
        $this->criarItem('Carteira preta', StatusItem::Disponivel); // cria a categoria "Documentos"

        $this->get('/')
            ->assertSee('Procure aqui')
            ->assertSee('Buscas rápidas:')
            ->assertSee('Documentos')
            ->assertSee('name="q"', false);
    }

    public function test_cabecalho_unico_com_links_e_botoes_para_visitante(): void
    {
        $this->get('/')
            ->assertSee('Todos os itens')
            ->assertSee('Fórum')
            ->assertSee('ENTRAR')
            ->assertSee('CRIAR CONTA')
            ->assertDontSee('bg-[#00988D]" aria-label="Principal"', false); // a barra verde separada não existe mais
    }

    public function test_marca_como_novo_apenas_os_itens_da_ultima_semana(): void
    {
        $this->criarItem('Item antigo', StatusItem::Disponivel, '2026-05-05');
        $this->get('/')->assertDontSee('>Novo</span>', false);

        $this->criarItem('Item de hoje', StatusItem::Disponivel, now()->toDateString());
        $this->get('/')->assertSee('>Novo</span>', false);
    }

    public function test_exibe_onde_fica_a_ddi_com_mapa_e_referencias(): void
    {
        $this->get('/')
            ->assertSee('Onde fica a')
            ->assertSee('Embaixo da COTEPS')
            ->assertSee('Ao lado da biblioteca')
            ->assertSee('Como chegar')
            ->assertSee('Prédio 4')
            ->assertDontSee('Abrir no Google Maps')
            ->assertSee('q=-16.719476,-43.8799577', false)
            ->assertSee('<iframe', false);
    }
}
