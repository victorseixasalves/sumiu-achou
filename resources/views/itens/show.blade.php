@php
    $rotas = \Illuminate\Support\Facades\Route::class;
    $reservado = $item->status === \App\Enums\StatusItem::Reservado;
    $ehNovo = ! $reservado && $item->data_achado->gte(now()->subDays(7)->startOfDay());

    // Ícone do "sem foto" de cada categoria (mesmos do card da lista)
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
    $icone = $caminhos[$iconePorCategoria[$item->categoria->nome] ?? 'caixa'];
    $tom = $tons[$item->categoria_id % 3];

    // "Achado hoje / ontem / há N dias"
    $dias = (int) floor(abs($item->data_achado->copy()->startOfDay()->diffInDays(now()->startOfDay())));
    $quando = match (true) {
        $dias === 0 => 'hoje',
        $dias === 1 => 'ontem',
        default => 'há '.$dias.' dias',
    };
    $mostrarEntrega = $item->data_entrega && ! $item->data_entrega->isSameDay($item->data_achado);

    $linkDevolucao = $rotas::has('devolucoes.create') ? route('devolucoes.create', $item) : null;
@endphp

<x-layouts.publico :titulo="$item->nome.' - Sumiu&Achou'">

    <section class="grupo pb-14 pt-8">
        <div class="mx-auto w-full max-w-[1240px] px-4 sm:px-6">

            {{-- Caminho --}}
            <nav class="item-anim flex flex-wrap items-center gap-2 text-xs font-bold text-[#29166F]/60" style="--i: 0;" aria-label="Você está em">
                <a href="{{ route('home') }}" class="transition hover:text-[#29166F]">Início</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('itens.index') }}" class="group inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-[#29166F] ring-1 ring-[#29166F]/15 transition hover:ring-[#00988D]">
                    <svg class="h-3 w-3 transition duration-200 group-hover:-translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"></path></svg>
                    Todos os itens
                </a>
                <span aria-hidden="true">/</span>
                <span class="max-w-[16ch] truncate text-[#29166F] sm:max-w-none" aria-current="page">{{ $item->nome }}</span>
            </nav>

            <div class="mt-6 grid gap-10 lg:grid-cols-[1.05fr_1fr] lg:gap-14">

                {{-- ============ FOTO ============ --}}
                <div class="item-anim lg:sticky lg:top-24 lg:self-start" style="--i: 1;">
                    <div class="relative aspect-[4/3] overflow-hidden rounded-[2rem] border-2 border-[#29166F] bg-white shadow-[8px_8px_0_#29166F]">
                        @if ($item->foto)
                            <img src="{{ asset('storage/'.$item->foto) }}" alt="Foto de {{ $item->nome }}" class="h-full w-full object-cover">
                        @else
                            <div class="relative flex h-full w-full items-center justify-center overflow-hidden" style="background: {{ $tom['fundo'] }}; color: {{ $tom['icone'] }};">
                                <span class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/55" aria-hidden="true"></span>
                                <span class="absolute -bottom-20 -left-14 h-56 w-56 rounded-full bg-white/40" aria-hidden="true"></span>

                                <div class="relative">
                                    <svg class="anel-pontilhado absolute -inset-5 h-[calc(100%+2.5rem)] w-[calc(100%+2.5rem)]" viewBox="0 0 100 100" fill="none" aria-hidden="true">
                                        <circle cx="50" cy="50" r="48" stroke="currentColor" stroke-width="0.9" stroke-linecap="round" stroke-dasharray="0.5 4"></circle>
                                    </svg>
                                    <span class="relative flex h-40 w-40 items-center justify-center rounded-full border-2 border-[#29166F] bg-white shadow-[6px_6px_0_#29166F] sm:h-48 sm:w-48">
                                        <svg class="h-20 w-20 sm:h-24 sm:w-24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icone !!}</svg>
                                    </span>
                                </div>

                                <span class="absolute bottom-4 left-5 text-xs font-bold uppercase tracking-wider opacity-70">Sem foto</span>
                            </div>
                        @endif

                        {{-- Selo inclinado, igual ao "FOI PERDIDO!" da home --}}
                        <span class="absolute left-4 top-4 -rotate-3 rounded-full bg-[#00988D] px-4 py-1.5 text-xs font-extrabold uppercase tracking-wider text-white shadow-[3px_3px_0_#29166F]">Achou!</span>

                        @if ($reservado)
                            <span class="absolute right-4 top-4 rounded-full bg-[#B6A9F0] px-3.5 py-1.5 text-xs font-extrabold uppercase tracking-wide text-[#29166F] shadow-sm">Reservado</span>
                        @elseif ($ehNovo)
                            <span class="absolute right-4 top-4 rounded-full bg-white px-3.5 py-1.5 text-xs font-extrabold uppercase tracking-wide text-[#00988D] shadow-sm">Novo</span>
                        @endif

                        <span class="absolute bottom-4 right-4 rounded-xl bg-[#29166F] px-3 py-1.5 text-xs font-bold text-white">Achado {{ $quando }}</span>
                    </div>
                </div>

                {{-- ============ INFORMAÇÕES ============ --}}
                <div class="min-w-0">

                    <div class="item-anim" style="--i: 2;">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#7B69B8] px-4 py-1.5 text-xs font-bold text-white shadow-[2px_2px_0_#29166F]">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12V4h8l10 10-8 8L3 12Z"></path><circle cx="7.5" cy="8.5" r="1.2"></circle></svg>
                                {{ $item->categoria->nome }}
                            </span>
                            @if ($reservado)
                                <span class="inline-flex items-center rounded-full bg-[#B6A9F0] px-4 py-1.5 text-xs font-bold text-[#29166F] shadow-[2px_2px_0_#29166F]">Reservado</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#00988D] px-4 py-1.5 text-xs font-bold text-white shadow-[2px_2px_0_#29166F]">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"></path></svg>
                                    Disponível
                                </span>
                            @endif
                        </div>

                        <h1 class="mt-4 break-words text-4xl font-extrabold leading-[1.05] tracking-tight sm:text-5xl lg:text-[3.4rem]">{{ $item->nome }}</h1>

                        <svg class="mt-2 h-3 w-40 text-[#00988D]" viewBox="0 0 160 12" fill="none" aria-hidden="true">
                            <path d="M2 6Q12 0 22 6T42 6T62 6T82 6T102 6T122 6T142 6T158 6" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path>
                        </svg>
                    </div>

                    {{-- Dados do item --}}
                    <dl class="item-anim mt-6 divide-y-2 divide-dashed divide-[#29166F]/12 rounded-3xl border-2 border-[#29166F] bg-white px-5 py-1 shadow-[6px_6px_0_#29166F]" style="--i: 3;">
                        <div class="group flex items-center gap-4 py-3.5">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#29166F] text-white transition duration-300 group-hover:-rotate-6 group-hover:scale-105">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s7-6.5 7-12a7 7 0 1 0-14 0c0 5.5 7 12 7 12Z"></path><circle cx="12" cy="10" r="2.5"></circle></svg>
                            </span>
                            <div class="min-w-0">
                                <dt class="text-[11px] font-extrabold uppercase tracking-wider text-[#29166F]/55">Achado em</dt>
                                <dd class="text-base font-bold">{{ $item->local->nome }}</dd>
                            </div>
                        </div>

                        <div class="group flex items-center gap-4 py-3.5">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#29166F] text-white transition duration-300 group-hover:-rotate-6 group-hover:scale-105">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2.5"></rect><path d="M3 10h18M8 3v4M16 3v4"></path></svg>
                            </span>
                            <div class="min-w-0">
                                <dt class="text-[11px] font-extrabold uppercase tracking-wider text-[#29166F]/55">Data em que foi achado</dt>
                                <dd class="text-base font-bold">{{ $item->data_achado->format('d/m/Y') }} <span class="text-sm font-semibold text-[#29166F]/55">· {{ $quando }}</span></dd>
                            </div>
                        </div>

                        @if ($mostrarEntrega)
                            <div class="group flex items-center gap-4 py-3.5">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#29166F] text-white transition duration-300 group-hover:-rotate-6 group-hover:scale-105">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12h13M12 6l6 6-6 6M21 4v16"></path></svg>
                                </span>
                                <div class="min-w-0">
                                    <dt class="text-[11px] font-extrabold uppercase tracking-wider text-[#29166F]/55">Entregue à DDI em</dt>
                                    <dd class="text-base font-bold">{{ $item->data_entrega->format('d/m/Y') }}</dd>
                                </div>
                            </div>
                        @endif

                        @if ($item->condicao)
                            <div class="group flex items-center gap-4 py-3.5">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#29166F] text-white transition duration-300 group-hover:-rotate-6 group-hover:scale-105">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8 12 3 3 8v8l9 5 9-5Z"></path><path d="m3 8 9 5 9-5M12 13v8"></path></svg>
                                </span>
                                <div class="min-w-0">
                                    <dt class="text-[11px] font-extrabold uppercase tracking-wider text-[#29166F]/55">Condição</dt>
                                    <dd class="text-base font-bold">{{ $item->condicao }}</dd>
                                </div>
                            </div>
                        @endif

                        <div class="group flex items-center gap-4 py-3.5">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#00988D] text-white transition duration-300 group-hover:-rotate-6 group-hover:scale-105">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"></path></svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <dt class="text-[11px] font-extrabold uppercase tracking-wider text-[#29166F]/55">Retirar na</dt>
                                <dd class="text-base font-bold">DDI · Prédio 4</dd>
                            </div>
                            <a href="{{ route('home') }}#onde-fica" class="shrink-0 rounded-full bg-[#D6EFEB] px-3 py-1.5 text-xs font-bold text-[#006F67] transition hover:bg-[#00988D] hover:text-white">Como chegar</a>
                        </div>
                    </dl>

                    {{-- Descrição --}}
                    <div class="item-anim mt-6" style="--i: 4;">
                        <h2 class="flex items-center gap-3 text-xs font-extrabold uppercase tracking-wider text-[#29166F]/55">
                            Descrição
                            <span class="h-0 flex-1 border-t-2 border-dashed border-[#29166F]/15"></span>
                        </h2>
                        <p class="mt-3 whitespace-pre-line text-base leading-relaxed text-[#29166F]/90">{{ $item->descricao ?: 'Este item ainda não tem uma descrição cadastrada.' }}</p>
                    </div>

                    {{-- Ação --}}
                    <div class="item-anim relative mt-8 overflow-hidden rounded-[1.75rem] bg-[#29166F] p-6 text-white shadow-[6px_6px_0_#00988D] sm:p-7" style="--i: 5;"
                         x-data="{ aviso: false }">
                        <svg class="anel-pontilhado pointer-events-none absolute -right-12 -top-12 h-44 w-44 text-white/25" viewBox="0 0 100 100" fill="none" aria-hidden="true">
                            <circle cx="50" cy="50" r="46" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-dasharray="1 6"></circle>
                        </svg>

                        <div class="relative">
                            <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.2em] text-[#B6A9F0]">
                                <span class="h-[3px] w-6 rounded-full bg-[#00C2B3]"></span>
                                Esse item é seu?
                            </p>
                            <h2 class="mt-2 text-2xl font-extrabold leading-tight">Peça a devolução em poucos passos</h2>

                            @if ($reservado)
                                <span class="mt-5 flex h-14 w-full cursor-not-allowed items-center justify-center rounded-full border-2 border-dashed border-white/35 bg-white/5 text-base font-extrabold text-white/60" aria-disabled="true">Item reservado</span>
                                <p class="mt-3 text-center text-xs font-medium text-[#DDD5FB]">Este item está reservado no momento.</p>
                            @else
                                @auth
                                    <a href="{{ $linkDevolucao ?? '#' }}" @if (! $linkDevolucao) @click.prevent="aviso = true" @endif
                                       class="group mt-5 flex h-14 w-full items-center justify-center gap-2 rounded-full bg-[#00988D] text-base font-extrabold text-white shadow-[0_5px_0_#006F67] transition duration-200 hover:-translate-y-0.5 hover:bg-[#00A99D] hover:shadow-[0_7px_0_#006F67] active:translate-y-1 active:shadow-[0_1px_0_#006F67]">
                                        É meu! Quero recuperar
                                        <svg class="h-4 w-4 transition duration-200 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                                    </a>
                                    <p class="mt-3 text-center text-xs font-medium text-[#DDD5FB]">Você vai precisar informar dados que comprovem que o item é seu.</p>
                                    <p x-show="aviso" x-cloak x-transition class="mt-3 rounded-2xl bg-white/10 px-4 py-3 text-center text-xs font-semibold" role="status">
                                        A solicitação de devolução será liberada em breve.
                                    </p>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="group mt-5 flex h-14 w-full items-center justify-center gap-2 rounded-full bg-[#00988D] text-base font-extrabold text-white shadow-[0_5px_0_#006F67] transition duration-200 hover:-translate-y-0.5 hover:bg-[#00A99D] hover:shadow-[0_7px_0_#006F67] active:translate-y-1 active:shadow-[0_1px_0_#006F67]">
                                        É meu! Quero recuperar
                                        <svg class="h-4 w-4 transition duration-200 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                                    </a>
                                    <p class="mt-3 text-center text-xs font-medium text-[#DDD5FB]">
                                        Precisa estar logado para reivindicar.
                                        <a href="{{ route('login') }}" class="font-bold text-[#00C2B3] underline-offset-4 hover:underline">Entrar</a>
                                        ou
                                        <a href="{{ route('register') }}" class="font-bold text-[#00C2B3] underline-offset-4 hover:underline">criar conta</a>
                                    </p>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ COMO RECUPERAR ============ --}}
    <section class="grupo pb-14">
        <div class="mx-auto w-full max-w-[1240px] px-4 sm:px-6">
            <div class="item-anim" style="--i: 0;">
                <h2 class="text-2xl font-extrabold sm:text-3xl">Como recuperar este item</h2>
                <p class="mt-1 text-sm text-[#29166F]/70">São só três passos, e a DDI cuida do resto.</p>
            </div>

            @php
                $passos = [
                    ['Entre na sua conta', 'Faça login ou crie sua conta para solicitar o item.', '<rect x="4" y="11" width="16" height="10" rx="2.5"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path>'],
                    ['Solicite a devolução', 'Clique em “É meu!” e informe dados que comprovem que o item é seu.', '<rect x="5" y="3" width="14" height="18" rx="2.5"></rect><path d="M9 8h6M9 12h6M9 16h3"></path>'],
                    ['Retire na DDI', 'Com a aprovação de um servidor, retire no Prédio 4 e receba o termo de entrega.', '<path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"></path>'],
                ];
            @endphp

            <div class="mt-7 grid gap-8 md:grid-cols-3 md:gap-10">
                @foreach ($passos as [$titulo, $texto, $icone])
                    <div class="item-anim relative" style="--i: {{ $loop->index + 1 }};">
                        <div class="h-full rounded-3xl border-2 border-[#29166F] bg-white p-5 shadow-[6px_6px_0_#29166F] transition duration-300 hover:-translate-y-1 hover:shadow-[9px_9px_0_#29166F]">
                            <div class="flex items-center justify-between">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#29166F] text-white">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icone !!}</svg>
                                </span>
                                <span class="rounded-full bg-[#7B69B8] px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-white">Passo {{ $loop->iteration }}</span>
                            </div>
                            <h3 class="mt-4 text-lg font-extrabold leading-tight">{{ $titulo }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-[#29166F]/75">{{ $texto }}</p>
                        </div>

                        @unless ($loop->last)
                            <span class="absolute -right-8 top-1/2 z-10 hidden h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border-2 border-[#29166F] bg-[#00988D] text-white md:flex" aria-hidden="true">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                            </span>
                        @endunless
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ ITENS PARECIDOS ============ --}}
    @if ($parecidos->isNotEmpty())
        <section class="grupo pb-16 pt-2">
            <div class="mx-auto w-full max-w-[1240px] px-4 sm:px-6">
                <div class="item-anim flex flex-wrap items-end justify-between gap-3" style="--i: 0;">
                    <div>
                        <h2 class="text-2xl font-extrabold sm:text-3xl">Itens parecidos</h2>
                        <p class="mt-1 text-sm text-[#29166F]/70">Outros itens da categoria {{ $item->categoria->nome }}.</p>
                    </div>
                    <a href="{{ route('itens.index', ['categoria' => [$item->categoria_id]]) }}" class="text-sm font-bold text-[#00988D] underline-offset-4 hover:underline">Ver todos desta categoria</a>
                </div>

                <div class="mt-6 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($parecidos as $parecido)
                        <x-item-card :item="$parecido" :indice="$loop->index + 1" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-layouts.publico>
