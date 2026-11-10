{{--
    Card de um item (usado no carrossel da homepage e na lista "Todos os itens").
    Uso: <x-item-card :item="$item" :indice="$loop->index" class="cartao" />
--}}
@props(['item', 'indice' => 0])

@php
    // Ícone e cor do "sem foto" de cada categoria (cada card fica diferente mesmo sem foto)
    $caminhos = [
        'cracha'  => '<rect x="3" y="5" width="18" height="14" rx="2.5"></rect><circle cx="9" cy="11" r="2"></circle><path d="M6.5 16c.5-1.5 1.5-2 2.5-2s2 .5 2.5 2M14 10h4M14 13h3"></path>',
        'celular' => '<rect x="7" y="2.5" width="10" height="19" rx="2.5"></rect><path d="M11 18.5h2"></path>',
        'camisa'  => '<path d="M8 3 3 6l2 4 3-1v12h8V9l3 1 2-4-5-3a4 4 0 0 1-8 0Z"></path>',
        'oculos'  => '<circle cx="6.5" cy="14" r="3.5"></circle><circle cx="17.5" cy="14" r="3.5"></circle><path d="M10 14h4M3.2 12.5 5 7M20.8 12.5 19 7"></path>',
        'caderno' => '<rect x="5" y="3" width="14" height="18" rx="2"></rect><path d="M9 3v18M12 8h4M12 12h4"></path>',
        'chave'   => '<circle cx="7.5" cy="15.5" r="4"></circle><path d="m10.5 12.5 9-9M16 7l3 3M13.5 9.5l2.5 2.5"></path>',
        'mochila' => '<path d="M9 6a3 3 0 0 1 6 0"></path><rect x="5" y="6" width="14" height="15" rx="4"></rect><path d="M9 15h6M9 11.5h6"></path>',
        'caixa'   => '<path d="M21 8 12 3 3 8v8l9 5 9-5Z"></path><path d="m3 8 9 5 9-5M12 13v8"></path>',
    ];
    $iconePorCategoria = [
        'Documentos' => 'cracha', 'Eletrônicos' => 'celular', 'Vestuário' => 'camisa', 'Acessórios' => 'oculos',
        'Material escolar' => 'caderno', 'Chaves' => 'chave', 'Bolsas e mochilas' => 'mochila',
    ];
    $tons = [
        ['fundo' => '#E7E1F7', 'icone' => '#7B69B8'],
        ['fundo' => '#D6EFEB', 'icone' => '#00988D'],
        ['fundo' => '#EDE8FB', 'icone' => '#9A8AD6'],
    ];

    $link = \Illuminate\Support\Facades\Route::has('itens.show') ? route('itens.show', $item) : '#';
    $icone = $caminhos[$iconePorCategoria[$item->categoria->nome] ?? 'caixa'];
    $tom = $tons[$item->categoria_id % 3];
    $reservado = $item->status === \App\Enums\StatusItem::Reservado;
    $ehNovo = ! $reservado && $item->data_achado->gte(now()->subDays(7)->startOfDay());
@endphp

<a href="{{ $link }}" style="--i: {{ $indice }};"
   {{ $attributes->merge(['class' => 'item-anim group block rounded-3xl border-2 border-[#29166F] bg-white p-2.5 shadow-[6px_6px_0_#29166F] transition duration-300 ease-out hover:-translate-y-1.5 hover:shadow-[9px_9px_0_#29166F] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#00988D] active:translate-y-0 active:shadow-[4px_4px_0_#29166F]']) }}>

    <div class="relative aspect-[4/3] overflow-hidden rounded-2xl">
        @if ($item->foto)
            <img src="{{ asset('storage/'.$item->foto) }}" alt="Foto de {{ $item->nome }}" loading="lazy"
                 class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
        @else
            <div class="relative flex h-full w-full items-center justify-center overflow-hidden" style="background: {{ $tom['fundo'] }}; color: {{ $tom['icone'] }};">
                <span class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/50" aria-hidden="true"></span>
                <span class="absolute -bottom-8 -left-6 h-24 w-24 rounded-full bg-white/35" aria-hidden="true"></span>
                <svg class="relative h-16 w-16 transition duration-500 group-hover:-rotate-6 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icone !!}</svg>
                <span class="absolute bottom-2 left-3 text-[10px] font-semibold uppercase tracking-wider opacity-60">Sem foto</span>
            </div>
        @endif

        <span class="absolute left-2.5 top-2.5 inline-flex max-w-[70%] items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-[10px] font-bold text-[#29166F] shadow-sm">
            <span class="h-1.5 w-1.5 shrink-0 rounded-full" style="background: {{ $tom['icone'] }};"></span>
            <span class="truncate">{{ $item->categoria->nome }}</span>
        </span>

        @if ($reservado)
            <span class="absolute right-2.5 top-2.5 rounded-full bg-[#B6A9F0] px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide text-[#29166F] shadow-sm">Reservado</span>
        @elseif ($ehNovo)
            <span class="absolute right-2.5 top-2.5 rounded-full bg-[#00988D] px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide text-white shadow-sm">Novo</span>
        @endif

        <span class="absolute bottom-2.5 right-2.5 rounded-lg bg-[#29166F] px-2 py-1 text-[11px] font-bold text-white">{{ $item->data_achado->format('d/m/y') }}</span>
    </div>

    <div class="px-1.5 pb-1 pt-3">
        <h3 class="truncate text-[15px] font-extrabold leading-tight">{{ $item->nome }}</h3>
        <p class="mt-1 flex items-center gap-1.5 text-xs text-[#29166F]/75">
            <svg class="h-3.5 w-3.5 shrink-0 text-[#00988D]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"></path></svg>
            <span class="truncate"><strong class="font-bold text-[#29166F]">Local:</strong> {{ $item->local->nome }}</span>
        </p>
    </div>
</a>
