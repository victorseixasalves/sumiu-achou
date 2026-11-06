<?php

namespace App\Http\Controllers;

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
            'resposta' => 'Os itens encontrados são entregues e ficam guardados na DDI. Você pode consultar todos eles aqui no sistema, na página "Todos os itens".',
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
            'resposta' => 'Entregue o item na DDI. Um servidor fará o cadastro no sistema para que o dono possa encontrá-lo.',
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
            ->limit(8)
            ->get();

        return view('home', [
            'itens' => $itens,
            'duvidas' => self::DUVIDAS,
        ]);
    }
}
