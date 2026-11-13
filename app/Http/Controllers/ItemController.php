<?php

namespace App\Http\Controllers;

use App\Enums\StatusItem;
use App\Models\Categoria;
use App\Models\Item;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ItemController extends Controller
{
    private const POR_PAGINA = 9;

    /**
     * Lista pública de itens, com busca (RF14), filtros combinados (RF17),
     * foto, resumo e status (RF15).
     */
    public function index(Request $request): View
    {
        $validador = Validator::make($request->query(), [
            'q' => ['nullable', 'string', 'max:100'],
            'local' => ['nullable', 'array'],
            'local.*' => ['integer'],
            'categoria' => ['nullable', 'array'],
            'categoria.*' => ['integer'],
            'de' => ['nullable', 'date'],
            'ate' => ['nullable', 'date', 'after_or_equal:de'],
        ], [
            'de.date' => 'Informe uma data inicial válida.',
            'ate.date' => 'Informe uma data final válida.',
            'ate.after_or_equal' => 'A data final precisa ser igual ou posterior à data inicial.',
        ]);

        // Só usa o que passou na validação (o que falhou é ignorado e o erro é mostrado na tela).
        $validos = $validador->valid();

        // Remove curingas do LIKE para o termo digitado ser tratado como texto comum.
        $termo = trim(str_replace(['%', '_', '\\'], '', (string) ($validos['q'] ?? '')));
        $locaisSelecionados = array_map('intval', $validos['local'] ?? []);
        $categoriasSelecionadas = array_map('intval', $validos['categoria'] ?? []);
        $de = $validos['de'] ?? null;
        $ate = $validos['ate'] ?? null;

        // Publicamente aparecem os itens disponíveis e os reservados (devolvidos e em triagem ficam de fora).
        $consulta = Item::query()
            ->with(['categoria', 'local'])
            ->whereIn('status', [StatusItem::Disponivel->value, StatusItem::Reservado->value]);

        // Cada palavra digitada precisa aparecer em algum campo (nome, descrição, condição, categoria ou local).
        foreach (preg_split('/\s+/', $termo, -1, PREG_SPLIT_NO_EMPTY) as $palavra) {
            $like = '%'.$palavra.'%';

            $consulta->where(function ($busca) use ($like) {
                $busca->where('nome', 'like', $like)
                    ->orWhere('descricao', 'like', $like)
                    ->orWhere('condicao', 'like', $like)
                    ->orWhereHas('categoria', fn ($c) => $c->where('nome', 'like', $like))
                    ->orWhereHas('local', fn ($l) => $l->where('nome', 'like', $like));
            });
        }

        if ($locaisSelecionados) {
            $consulta->whereIn('local_id', $locaisSelecionados);
        }

        if ($categoriasSelecionadas) {
            $consulta->whereIn('categoria_id', $categoriasSelecionadas);
        }

        if ($de) {
            $consulta->whereDate('data_achado', '>=', $de);
        }

        if ($ate) {
            $consulta->whereDate('data_achado', '<=', $ate);
        }

        $itens = $consulta
            ->orderByDesc('data_achado')
            ->orderByDesc('id')
            ->paginate(self::POR_PAGINA)
            ->withQueryString();

        return view('itens.index', [
            'itens' => $itens,
            'locais' => Local::orderBy('nome')->get(),
            'categorias' => Categoria::orderBy('nome')->get(),
            'termo' => (string) ($validos['q'] ?? ''),
            'locaisSelecionados' => $locaisSelecionados,
            'categoriasSelecionadas' => $categoriasSelecionadas,
            'de' => $de,
            'ate' => $ate,
        ])->withErrors($validador);
    }

    /**
     * Página de detalhe de um item (RF16).
     * Só itens disponíveis ou reservados são públicos: devolvidos, em triagem, destinados
     * e excluídos logicamente respondem 404. Nenhum dado pessoal é exibido (RN04).
     */
    public function show(Item $item): View
    {
        abort_unless(
            in_array($item->status, [StatusItem::Disponivel, StatusItem::Reservado], true),
            404
        );

        $item->load(['categoria', 'local']);

        // Outros itens da mesma categoria, para quem não achou o seu aqui.
        $parecidos = Item::query()
            ->with(['categoria', 'local'])
            ->where('categoria_id', $item->categoria_id)
            ->whereKeyNot($item->getKey())
            ->whereIn('status', [StatusItem::Disponivel->value, StatusItem::Reservado->value])
            ->orderByDesc('data_achado')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        return view('itens.show', [
            'item' => $item,
            'parecidos' => $parecidos,
        ]);
    }
}
