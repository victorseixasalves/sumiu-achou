@php
    $filtrosAtivos = count($locaisSelecionados) + count($categoriasSelecionadas) + ($de ? 1 : 0) + ($ate ? 1 : 0);
    $temFiltro = $termo !== '' || $filtrosAtivos > 0;

    // Monta o endereço da lista sem um filtro específico (usado nos "x" dos filtros ativos)
    $semFiltro = function (string $campo, $valor = null) {
        $consulta = request()->query();
        unset($consulta['page']);

        if ($valor === null) {
            unset($consulta[$campo]);
        } else {
            $restantes = array_values(array_filter((array) ($consulta[$campo] ?? []), fn ($v) => (string) $v !== (string) $valor));
            if ($restantes) {
                $consulta[$campo] = $restantes;
            } else {
                unset($consulta[$campo]);
            }
        }

        return route('itens.index', $consulta);
    };
@endphp

<x-layouts.publico titulo="Todos os itens - Sumiu&Achou">

    <section class="grupo pb-16 pt-10" x-data="{ filtros: {{ $filtrosAtivos > 0 ? 'true' : 'false' }} }">
        <div class="mx-auto w-full max-w-[1240px] px-4 sm:px-6">

            {{-- Título --}}
            <div class="item-anim flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold sm:text-4xl">Todos os itens perdidos:</h1>
                    <p class="mt-1 text-sm text-[#29166F]/70">Itens encontrados e entregues à DDI.</p>
                </div>

                <p class="rounded-full bg-white/80 px-4 py-1.5 text-sm font-semibold text-[#29166F] ring-1 ring-[#29166F]/15">
                    @if ($itens->total() > 0)
                        Mostrando <strong class="font-extrabold">{{ $itens->firstItem() }}–{{ $itens->lastItem() }}</strong>
                        de <strong class="font-extrabold">{{ $itens->total() }}</strong> {{ $itens->total() === 1 ? 'item' : 'itens' }}
                    @else
                        Nenhum item encontrado
                    @endif
                </p>
            </div>

            <form method="GET" action="{{ route('itens.index') }}" class="mt-6">

                {{-- Busca --}}
                <div class="item-anim flex items-center gap-2 rounded-full border-2 border-[#29166F] bg-white p-1.5 pl-5 shadow-[0_6px_0_#29166F] ring-4 ring-transparent transition duration-300 focus-within:-translate-y-0.5 focus-within:shadow-[0_8px_0_#29166F] focus-within:ring-[#00988D]/25 sm:gap-3 sm:p-2 sm:pl-6" style="--i: 1;">
                    <svg class="h-5 w-5 shrink-0 text-[#00988D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path>
                    </svg>
                    <label for="q" class="sr-only">Buscar item</label>
                    <input id="q" name="q" type="search" value="{{ $termo }}" autocomplete="off" placeholder="Buscar por nome, cor, marca..."
                           class="h-11 min-w-0 flex-1 border-0 bg-transparent px-0 text-base text-[#29166F] shadow-none placeholder-[#29166F]/45 focus:border-0 focus:outline-none focus:ring-0">
                    <button type="submit" aria-label="Buscar"
                            class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-full bg-[#29166F] px-5 text-sm font-bold text-white transition duration-300 hover:bg-[#1F0F5C] active:scale-95 sm:px-7">
                        <span class="hidden sm:inline">Buscar</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                    </button>
                </div>

                {{-- Botão para abrir os filtros no celular --}}
                <button type="button" @click="filtros = !filtros" :aria-expanded="filtros"
                        class="mt-5 inline-flex h-11 items-center gap-2 rounded-full border-2 border-[#29166F] bg-white px-5 text-sm font-bold text-[#29166F] shadow-[0_3px_0_#29166F] transition duration-200 active:translate-y-0.5 active:shadow-none lg:hidden">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 5h18l-7 8v6l-4-2v-4Z"></path></svg>
                    Filtros
                    @if ($filtrosAtivos > 0)
                        <span class="flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-[#00988D] px-1.5 text-[11px] font-extrabold text-white">{{ $filtrosAtivos }}</span>
                    @endif
                </button>

                <div class="mt-6 grid gap-8 lg:grid-cols-[290px_1fr] lg:items-start">

                    {{-- ============ FILTROS ============ --}}
                    <aside class="item-anim rounded-3xl border-2 border-[#29166F] bg-white p-5 shadow-[6px_6px_0_#29166F] lg:sticky lg:top-24 lg:block"
                           :class="filtros ? 'block' : 'hidden'" style="--i: 2;">

                        <div class="flex items-center justify-between">
                            <h2 class="flex items-center gap-2 text-lg font-extrabold">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#00988D] text-white">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 5h18l-7 8v6l-4-2v-4Z"></path></svg>
                                </span>
                                Filtros
                            </h2>
                            @if ($filtrosAtivos > 0)
                                <span class="rounded-full bg-[#00988D]/15 px-2.5 py-1 text-xs font-bold text-[#00988D]">{{ $filtrosAtivos }} {{ $filtrosAtivos === 1 ? 'ativo' : 'ativos' }}</span>
                            @endif
                        </div>

                        {{-- Local --}}
                        <fieldset class="mt-5">
                            <legend class="text-[11px] font-extrabold uppercase tracking-wider text-[#29166F]/60">Local</legend>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach ($locais as $local)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="local[]" value="{{ $local->id }}" class="peer sr-only" @checked(in_array($local->id, $locaisSelecionados))>
                                        <span class="inline-flex items-center rounded-full border-2 border-[#29166F]/20 bg-white px-3 py-1.5 text-xs font-bold text-[#29166F] transition duration-200 hover:border-[#00988D] peer-checked:border-[#29166F] peer-checked:bg-[#00988D] peer-checked:text-white peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-[#00988D]">{{ $local->nome }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        {{-- Categoria --}}
                        <fieldset class="mt-5">
                            <legend class="text-[11px] font-extrabold uppercase tracking-wider text-[#29166F]/60">Categoria</legend>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach ($categorias as $categoria)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="categoria[]" value="{{ $categoria->id }}" class="peer sr-only" @checked(in_array($categoria->id, $categoriasSelecionadas))>
                                        <span class="inline-flex items-center rounded-full border-2 border-[#29166F]/20 bg-white px-3 py-1.5 text-xs font-bold text-[#29166F] transition duration-200 hover:border-[#00988D] peer-checked:border-[#29166F] peer-checked:bg-[#00988D] peer-checked:text-white peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-[#00988D]">{{ $categoria->nome }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        {{-- Data --}}
                        <fieldset class="mt-5">
                            <legend class="text-[11px] font-extrabold uppercase tracking-wider text-[#29166F]/60">Data em que foi achado</legend>
                            <div class="mt-2 grid grid-cols-2 gap-2 lg:grid-cols-1">
                                <label class="block">
                                    <span class="mb-1 block text-xs font-semibold text-[#29166F]/70">De</span>
                                    <input type="date" name="de" value="{{ $de }}"
                                           class="h-11 w-full rounded-xl border-2 border-[#29166F]/25 bg-white px-3 text-sm text-[#29166F] focus:border-[#00988D] focus:ring-0">
                                </label>
                                <label class="block">
                                    <span class="mb-1 block text-xs font-semibold text-[#29166F]/70">Até</span>
                                    <input type="date" name="ate" value="{{ $ate }}"
                                           class="h-11 w-full rounded-xl border-2 border-[#29166F]/25 bg-white px-3 text-sm text-[#29166F] focus:border-[#00988D] focus:ring-0">
                                </label>
                            </div>
                            @error('de') <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                            @error('ate') <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                        </fieldset>

                        {{-- Ações --}}
                        <div class="mt-6">
                            <button type="submit"
                                    class="flex h-12 w-full items-center justify-center rounded-full bg-[#29166F] text-sm font-bold text-white shadow-[0_5px_0_#1B0E52] transition duration-200 hover:-translate-y-0.5 hover:bg-[#1F0F5C] hover:shadow-[0_7px_0_#1B0E52] active:translate-y-0.5 active:shadow-[0_1px_0_#1B0E52]">
                                Aplicar filtros
                            </button>
                            <a href="{{ route('itens.index') }}"
                               class="mt-3 block text-center text-sm font-semibold text-[#29166F]/70 underline-offset-4 transition hover:text-[#00988D] hover:underline">Limpar filtros</a>
                        </div>
                    </aside>

                    {{-- ============ RESULTADOS ============ --}}
                    <div class="min-w-0">

                        {{-- Filtros ativos (cada um pode ser removido com o "x") --}}
                        @if ($temFiltro)
                            <div class="mb-6 flex flex-wrap items-center gap-2">
                                <span class="text-xs font-semibold text-[#29166F]/60">Filtros ativos:</span>

                                @if ($termo !== '')
                                    <a href="{{ $semFiltro('q') }}" class="group inline-flex items-center gap-1.5 rounded-full bg-[#29166F] px-3 py-1 text-xs font-bold text-white transition hover:bg-[#1F0F5C]">
                                        “{{ $termo }}”
                                        <svg class="h-3 w-3 transition group-hover:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"></path></svg>
                                        <span class="sr-only">Remover busca</span>
                                    </a>
                                @endif

                                @foreach ($locais->whereIn('id', $locaisSelecionados) as $local)
                                    <a href="{{ $semFiltro('local', $local->id) }}" class="group inline-flex items-center gap-1.5 rounded-full bg-[#00988D] px-3 py-1 text-xs font-bold text-white transition hover:bg-[#007F75]">
                                        {{ $local->nome }}
                                        <svg class="h-3 w-3 transition group-hover:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"></path></svg>
                                        <span class="sr-only">Remover filtro de local</span>
                                    </a>
                                @endforeach

                                @foreach ($categorias->whereIn('id', $categoriasSelecionadas) as $categoria)
                                    <a href="{{ $semFiltro('categoria', $categoria->id) }}" class="group inline-flex items-center gap-1.5 rounded-full bg-[#7B69B8] px-3 py-1 text-xs font-bold text-white transition hover:bg-[#6853B0]">
                                        {{ $categoria->nome }}
                                        <svg class="h-3 w-3 transition group-hover:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"></path></svg>
                                        <span class="sr-only">Remover filtro de categoria</span>
                                    </a>
                                @endforeach

                                @if ($de)
                                    <a href="{{ $semFiltro('de') }}" class="group inline-flex items-center gap-1.5 rounded-full bg-[#B6A9F0] px-3 py-1 text-xs font-bold text-[#29166F] transition hover:bg-[#A89AE8]">
                                        A partir de {{ \Illuminate\Support\Carbon::parse($de)->format('d/m/y') }}
                                        <svg class="h-3 w-3 transition group-hover:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"></path></svg>
                                        <span class="sr-only">Remover data inicial</span>
                                    </a>
                                @endif

                                @if ($ate)
                                    <a href="{{ $semFiltro('ate') }}" class="group inline-flex items-center gap-1.5 rounded-full bg-[#B6A9F0] px-3 py-1 text-xs font-bold text-[#29166F] transition hover:bg-[#A89AE8]">
                                        Até {{ \Illuminate\Support\Carbon::parse($ate)->format('d/m/y') }}
                                        <svg class="h-3 w-3 transition group-hover:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"></path></svg>
                                        <span class="sr-only">Remover data final</span>
                                    </a>
                                @endif

                                <a href="{{ route('itens.index') }}" class="ml-1 text-xs font-bold text-[#00988D] underline-offset-4 hover:underline">Limpar tudo</a>
                            </div>
                        @endif

                        @if ($itens->count() > 0)
                            <div class="grid gap-7 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($itens as $item)
                                    <x-item-card :item="$item" :indice="$loop->index" />
                                @endforeach
                            </div>

                            <div class="mt-12">
                                {{ $itens->links('pagination.sumiu') }}
                            </div>
                        @else
                            <div class="item-anim flex flex-col items-center gap-3 rounded-3xl border-2 border-dashed border-[#29166F]/30 bg-white/70 px-6 py-16 text-center">
                                <svg class="h-12 w-12 text-[#29166F]/40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="10.5" cy="10.5" r="6.5"></circle><path d="m20 20-4.8-4.8M8 10.5h5"></path>
                                </svg>
                                <p class="text-lg font-extrabold">Nenhum item encontrado</p>
                                <p class="max-w-[420px] text-sm text-[#29166F]/75">
                                    @if ($temFiltro)
                                        Tente buscar por outra palavra ou remova alguns filtros.
                                    @else
                                        Ainda não há itens cadastrados. Volte em breve!
                                    @endif
                                </p>
                                @if ($temFiltro)
                                    <a href="{{ route('itens.index') }}"
                                       class="mt-2 inline-flex h-11 items-center rounded-full bg-[#00988D] px-6 text-sm font-bold text-white shadow-[0_4px_0_#006F67] transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_6px_0_#006F67] active:translate-y-0.5 active:shadow-none">
                                        Limpar filtros
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </section>

</x-layouts.publico>
