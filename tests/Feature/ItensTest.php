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
 * Lista pública de itens: busca, filtros combinados e paginação (RF14 a RF17).
 */
class ItensTest extends TestCase
{
    use RefreshDatabase;

    private function criarItem(
        string $nome,
        StatusItem $status = StatusItem::Disponivel,
        string $data = '2026-05-05',
        string $local = 'CCET',
        string $categoria = 'Documentos',
        ?string $descricao = null,
    ): Item {
        $servidor = User::firstOrCreate(
            ['email' => 'ddi@teste.local'],
            ['name' => 'Servidor', 'password' => 'password']
        );

        return Item::create([
            'nome' => $nome,
            'categoria_id' => Categoria::firstOrCreate(['nome' => $categoria])->id,
            'local_id' => Local::firstOrCreate(['nome' => $local])->id,
            'data_achado' => $data,
            'data_entrega' => $data,
            'descricao' => $descricao,
            'status' => $status,
            'usuario_cadastro_id' => $servidor->id,
        ]);
    }

    private function titulo(string $nome): string
    {
        return '>'.$nome.'</h3>';
    }

    public function test_lista_e_publica_e_mostra_disponiveis_e_reservados(): void
    {
        $this->criarItem('Carteira preta', StatusItem::Disponivel);
        $this->criarItem('Mochila azul', StatusItem::Reservado);
        $this->criarItem('Chave velha', StatusItem::Devolvido);

        $this->get(route('itens.index'))
            ->assertOk()
            ->assertSee('Todos os itens perdidos:')
            ->assertSee($this->titulo('Carteira preta'), false)
            ->assertSee($this->titulo('Mochila azul'), false)
            ->assertDontSee($this->titulo('Chave velha'), false)
            ->assertSee('Reservado');
    }

    public function test_busca_por_palavra_no_nome(): void
    {
        $this->criarItem('Carteira preta');
        $this->criarItem('Carteira marrom');
        $this->criarItem('Mochila azul');

        $this->get(route('itens.index', ['q' => 'carteira']))
            ->assertSee($this->titulo('Carteira preta'), false)
            ->assertSee($this->titulo('Carteira marrom'), false)
            ->assertDontSee($this->titulo('Mochila azul'), false);
    }

    public function test_busca_com_duas_palavras_exige_as_duas(): void
    {
        $this->criarItem('Carteira preta');
        $this->criarItem('Carteira marrom');

        $this->get(route('itens.index', ['q' => 'carteira preta']))
            ->assertSee($this->titulo('Carteira preta'), false)
            ->assertDontSee($this->titulo('Carteira marrom'), false);
    }

    public function test_busca_tambem_olha_a_descricao(): void
    {
        $this->criarItem('Estojo', descricao: 'Zíper dourado e chaveiro de gato');
        $this->criarItem('Garrafa', descricao: 'Térmica azul');

        $this->get(route('itens.index', ['q' => 'dourado']))
            ->assertSee($this->titulo('Estojo'), false)
            ->assertDontSee($this->titulo('Garrafa'), false);
    }

    public function test_busca_ignora_curingas_do_like(): void
    {
        $this->criarItem('Carteira preta');

        // "%" e "_" são removidos: o termo vira "carteira" e continua achando o item.
        $this->get(route('itens.index', ['q' => '%carteira_']))
            ->assertSee($this->titulo('Carteira preta'), false);
    }

    public function test_filtra_por_local(): void
    {
        $this->criarItem('Item no CCET', local: 'CCET');
        $this->criarItem('Item na Biblioteca', local: 'Biblioteca');
        $biblioteca = Local::where('nome', 'Biblioteca')->firstOrFail();

        $this->get(route('itens.index', ['local' => [$biblioteca->id]]))
            ->assertSee($this->titulo('Item na Biblioteca'), false)
            ->assertDontSee($this->titulo('Item no CCET'), false);
    }

    public function test_filtra_por_categoria(): void
    {
        $this->criarItem('Cracha antigo', categoria: 'Documentos');
        $this->criarItem('Fone sem fio', categoria: 'Eletrônicos');
        $eletronicos = Categoria::where('nome', 'Eletrônicos')->firstOrFail();

        $this->get(route('itens.index', ['categoria' => [$eletronicos->id]]))
            ->assertSee($this->titulo('Fone sem fio'), false)
            ->assertDontSee($this->titulo('Cracha antigo'), false);
    }

    public function test_filtra_por_periodo(): void
    {
        $this->criarItem('Item de abril', data: '2026-04-30');
        $this->criarItem('Item de maio', data: '2026-05-03');
        $this->criarItem('Item de junho', data: '2026-06-10');

        $this->get(route('itens.index', ['de' => '2026-05-01', 'ate' => '2026-05-31']))
            ->assertSee($this->titulo('Item de maio'), false)
            ->assertDontSee($this->titulo('Item de abril'), false)
            ->assertDontSee($this->titulo('Item de junho'), false);
    }

    public function test_combina_busca_local_categoria_e_periodo(): void
    {
        $this->criarItem('Carteira alvo', data: '2026-05-03', local: 'CCET', categoria: 'Documentos');
        $this->criarItem('Carteira em outro local', data: '2026-05-03', local: 'Cantina', categoria: 'Documentos');
        $this->criarItem('Carteira em outra categoria', data: '2026-05-03', local: 'CCET', categoria: 'Acessórios');
        $this->criarItem('Carteira fora do periodo', data: '2026-01-10', local: 'CCET', categoria: 'Documentos');

        $this->get(route('itens.index', [
            'q' => 'carteira',
            'local' => [Local::where('nome', 'CCET')->value('id')],
            'categoria' => [Categoria::where('nome', 'Documentos')->value('id')],
            'de' => '2026-05-01',
            'ate' => '2026-05-31',
        ]))
            ->assertSee($this->titulo('Carteira alvo'), false)
            ->assertDontSee($this->titulo('Carteira em outro local'), false)
            ->assertDontSee($this->titulo('Carteira em outra categoria'), false)
            ->assertDontSee($this->titulo('Carteira fora do periodo'), false);
    }

    public function test_pagina_de_nove_em_nove_itens(): void
    {
        foreach (range(1, 12) as $n) {
            $this->criarItem("Item numero {$n}", data: sprintf('2026-05-%02d', $n));
        }

        $this->get(route('itens.index'))
            ->assertSee($this->titulo('Item numero 12'), false)
            ->assertSee($this->titulo('Item numero 4'), false)
            ->assertDontSee($this->titulo('Item numero 3'), false)
            ->assertSee('Mostrando')
            ->assertSee('aria-label="Próxima página"', false);

        $this->get(route('itens.index', ['page' => 2]))
            ->assertSee($this->titulo('Item numero 3'), false)
            ->assertSee($this->titulo('Item numero 1'), false)
            ->assertDontSee($this->titulo('Item numero 12'), false);
    }

    public function test_sem_resultados_mostra_mensagem_e_atalho_para_limpar(): void
    {
        $this->criarItem('Carteira preta');

        $this->get(route('itens.index', ['q' => 'xyzinexistente']))
            ->assertOk()
            ->assertSee('Nenhum item encontrado')
            ->assertSee('Limpar filtros');
    }

    public function test_data_final_anterior_a_inicial_mostra_erro_e_nao_quebra(): void
    {
        $this->criarItem('Carteira preta');

        $this->get(route('itens.index', ['de' => '2026-05-10', 'ate' => '2026-05-01']))
            ->assertOk()
            ->assertSee('A data final precisa ser igual ou posterior à data inicial.');
    }

    public function test_mostra_filtros_ativos_com_opcao_de_remover(): void
    {
        $this->criarItem('Carteira preta', local: 'CCET');
        $ccet = Local::where('nome', 'CCET')->firstOrFail();

        $this->get(route('itens.index', ['q' => 'carteira', 'local' => [$ccet->id]]))
            ->assertSee('Filtros ativos:')
            ->assertSee('Remover busca')
            ->assertSee('Remover filtro de local');
    }

    public function test_link_da_homepage_e_o_cabecalho_levam_para_a_lista(): void
    {
        $this->get('/')
            ->assertSee(route('itens.index'), false);
    }

    public function test_estrutura_da_busca_ao_vivo_e_alternativa_sem_javascript(): void
    {
        $this->get(route('itens.index'))
            ->assertSee('id="resultados"', false)
            ->assertSee('id="resumo"', false)
            ->assertSee('data-atualizar', false)
            ->assertSee('name="q"', false)
            ->assertSee('Aplicar filtros'); // botão que só aparece para quem está sem JavaScript
    }

    public function test_exibe_atalhos_de_periodo_nos_filtros(): void
    {
        $this->get(route('itens.index'))
            ->assertSee('7 dias')
            ->assertSee('30 dias')
            ->assertSee('90 dias');
    }

    public function test_marca_os_filtros_escolhidos_ao_abrir_a_pagina(): void
    {
        $this->criarItem('Carteira preta', local: 'CCET');
        $ccet = Local::where('nome', 'CCET')->firstOrFail();

        $this->get(route('itens.index', ['local' => [$ccet->id]]))
            ->assertSee('name="local[]" value="'.$ccet->id.'"', false)
            ->assertSee('checked', false);
    }
}
