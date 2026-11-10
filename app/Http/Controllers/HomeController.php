<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Item;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Perguntas frequentes exibidas na página inicial.
     */
    private const DUVIDAS = [
        [
            'pergunta' => 'Onde ficam os itens perdidos?',
            'resposta' => 'Os itens encontrados ficam guardados na DDI, no Prédio 4, embaixo da COTEPS e ao lado da biblioteca. Você também pode consultar todos eles aqui no sistema, na página "Todos os itens".',
        ],
        [
            'pergunta' => 'Posso anunciar algo que eu perdi, ou só vejo o que acharam?',
            'resposta' => 'Você pode fazer as duas coisas. Depois de entrar na sua conta, registre o que perdeu e consulte os itens que já foram encontrados.',
        ],
        [
            'pergunta' => 'Como faço para recuperar meu item perdido?',
            'resposta' => 'Encontre o item em "Todos os itens", entre na sua conta e solicite a devolução, informando dados que comprovem que ele é seu. A DDI analisa o pedido e, sendo aprovado, a entrega é registrada em um termo digital.',
        ],
        [
            'pergunta' => 'Achei um item. Onde devo entregar?',
            'resposta' => 'Entregue o item na DDI, no Prédio 4, embaixo da COTEPS e ao lado da biblioteca. Um servidor fará o cadastro no sistema para que o dono possa encontrá-lo.',
        ],
    ];

    /**
     * Passos da seção "Como funciona".
     */
    private const PASSOS = [
        [
            'icone' => 'caixa',
            'titulo' => 'Achou algo? Leve até a DDI',
            'texto' => 'O item é entregue na DDI, e um servidor faz o cadastro com foto, local e data para que o dono consiga encontrá-lo.',
        ],
        [
            'icone' => 'lupa',
            'titulo' => 'Procure no sistema',
            'texto' => 'Pesquise por palavra-chave, categoria, local ou período e veja a foto e a situação de cada item encontrado.',
        ],
        [
            'icone' => 'chave',
            'titulo' => 'Solicite a devolução',
            'texto' => 'Entre na sua conta, informe dados que provem que o item é seu e aguarde a análise. Se aprovado, a entrega é registrada em um termo digital.',
        ],
    ];

    /**
     * Página inicial pública.
     */
    public function index(): View
    {
        $itens = Item::with(['local', 'categoria'])
            ->disponiveis()
            ->orderByDesc('data_achado')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('home', [
            'itens' => $itens,
            'duvidas' => self::DUVIDAS,
            'passos' => self::PASSOS,
            'categorias' => Categoria::orderBy('nome')->limit(6)->pluck('nome'),
        ]);
    }
}
