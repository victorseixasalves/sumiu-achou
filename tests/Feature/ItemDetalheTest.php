<?php

namespace Tests\Feature;

use App\Enums\StatusItem;
use App\Models\Categoria;
use App\Models\Item;
use App\Models\Local;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Página de detalhe do item (RF16).
 */
class ItemDetalheTest extends TestCase
{
    use RefreshDatabase;

    private function criarItem(string $nome = 'Carteira preta', StatusItem $status = StatusItem::Disponivel, string $categoria = 'Documentos', ?string $descricao = 'Carteira de couro preta com zíper.'): Item
    {
        $servidor = User::firstOrCreate(
            ['email' => 'ddi@teste.local'],
            ['name' => 'Servidor', 'password' => 'password']
        );

        return Item::create([
            'nome' => $nome,
            'categoria_id' => Categoria::firstOrCreate(['nome' => $categoria])->id,
            'local_id' => Local::firstOrCreate(['nome' => 'CCET'])->id,
            'data_achado' => '2026-05-05',
            'data_entrega' => '2026-05-05',
            'descricao' => $descricao,
            'status' => $status,
            'usuario_cadastro_id' => $servidor->id,
        ]);
    }

    public function test_detalhe_e_publico_e_mostra_os_dados_do_item(): void
    {
        $item = $this->criarItem();

        $this->get(route('itens.show', $item))
            ->assertOk()
            ->assertSee('Carteira preta')
            ->assertSee('Documentos')
            ->assertSee('Disponível')
            ->assertSee('CCET')
            ->assertSee('05/05/2026')
            ->assertSee('DDI · Prédio 4')
            ->assertSee('Carteira de couro preta com zíper.')
            ->assertSee('Como recuperar este item');
    }

    public function test_visitante_ve_o_botao_e_o_aviso_de_login(): void
    {
        $item = $this->criarItem();

        $this->get(route('itens.show', $item))
            ->assertSee('É meu! Quero recuperar')
            ->assertSee('Precisa estar logado para reivindicar')
            ->assertSee(route('login'), false);
    }

    public function test_usuario_logado_nao_ve_o_aviso_de_login(): void
    {
        $item = $this->criarItem();

        $this->actingAs(User::factory()->create())
            ->get(route('itens.show', $item))
            ->assertOk()
            ->assertSee('É meu! Quero recuperar')
            ->assertDontSee('Precisa estar logado para reivindicar');
    }

    public function test_item_reservado_aparece_mas_nao_pode_ser_reivindicado(): void
    {
        $item = $this->criarItem('Mochila azul', StatusItem::Reservado, 'Bolsas e mochilas');

        $this->get(route('itens.show', $item))
            ->assertOk()
            ->assertSee('Item reservado')
            ->assertDontSee('É meu! Quero recuperar');
    }

    public function test_item_devolvido_ou_em_triagem_responde_404(): void
    {
        $devolvido = $this->criarItem('Chave velha', StatusItem::Devolvido);
        $triagem = $this->criarItem('Estojo antigo', StatusItem::EmTriagem);

        $this->get(route('itens.show', $devolvido))->assertNotFound();
        $this->get(route('itens.show', $triagem))->assertNotFound();
    }

    public function test_item_excluido_logicamente_responde_404(): void
    {
        $item = $this->criarItem();
        $item->delete();

        $this->get(route('itens.show', $item->getKey()))->assertNotFound();
    }

    public function test_item_inexistente_responde_404(): void
    {
        $this->get('/itens/9999')->assertNotFound();
    }

    public function test_sem_descricao_mostra_texto_padrao(): void
    {
        $item = $this->criarItem(descricao: null);

        $this->get(route('itens.show', $item))->assertSee('ainda não tem uma descrição');
    }

    public function test_mostra_itens_parecidos_da_mesma_categoria(): void
    {
        $item = $this->criarItem('Carteira preta');
        $this->criarItem('Crachá de estudante');
        $this->criarItem('Mochila azul', StatusItem::Disponivel, 'Bolsas e mochilas');

        $this->get(route('itens.show', $item))
            ->assertSee('Itens parecidos')
            ->assertSee('>Crachá de estudante</h3>', false)
            ->assertDontSee('>Mochila azul</h3>', false);
    }

    public function test_cards_da_lista_apontam_para_o_detalhe(): void
    {
        $item = $this->criarItem();

        $this->get(route('itens.index'))->assertSee(route('itens.show', $item), false);
    }
}
