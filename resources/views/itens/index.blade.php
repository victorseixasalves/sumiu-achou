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

    {{--
        A busca e os filtros funcionam "ao vivo": enquanto a pessoa digita ou marca uma opção, a lista é atualizada
        sem recarregar a página (fetch). Sem JavaScript, o formulário continua funcionando normalmente (botões "Buscar" e "Aplicar filtros").
    --}}
    <section class="grupo pb-16 pt-10"
             x-data="{
                 filtros: {{ $filtrosAtivos > 0 ? 'true' : 'false' }},
                 carregando: false,
                 termo: @js($termo),
                 locais: @js($locaisSelecionados),
                 categorias: @js($categoriasSelecionadas),
                 de: @js($de ?? ''),
                 ate: @js($ate ?? ''),
                 temporizador: null,
                 controle: null,
                 get total() { return this.locais.length + this.categorias.length + (this.de ? 1 : 0) + (this.ate ? 1 : 0); },
                 buscar(imediato = false) {
                     clearTimeout(this.temporizador);
                     this.temporizador = setTimeout(() => this.carregar(), imediato ? 0 : 350);
                 },
                 async carregar(url = null) {
                     const formulario = this.$refs.form;
                     const parametros = new URLSearchParams(new FormData(formulario));
                     for (const [chave, valor] of [...parametros.entries()]) { if (valor === '') parametros.delete(chave); }
                     const consulta = parametros.toString();
                     const destino = url || (formulario.getAttribute('action') + (consulta ? '?' + consulta : ''));

                     if (this.controle) this.controle.abort();
                     this.controle = new AbortController();
                     this.carregando = true;

                     try {
                         const resposta = await fetch(destino, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: this.controle.signal });
                         if (!resposta.ok) throw new Error('falha');
                         const documento = new DOMParser().parseFromString(await resposta.text(), 'text/html');
                         documento.querySelectorAll('[data-atualizar]').forEach((novo) => {
                             const atual = document.getElementById(novo.id);
                             if (atual) atual.innerHTML = novo.innerHTML;
                         });
                         history.replaceState(null, '', destino);
                     } catch (erro) {
                         if (erro.name !== 'AbortError') window.location.href = destino;
                     } finally {
                         this.carregando = false;
                     }
                 },
                 irPagina(evento) {
                     const link = evento.target.closest('a[href]');
                     if (!link || !link.closest('.paginacao-itens')) return;
                     evento.preventDefault();
                     this.carregar(link.href).then(() => this.$refs.resultados.scrollIntoView({ behavior: 'smooth', block: 'start' }));
                 },
                 periodo(dias) {
                     const hoje = new Date();
                     const inicio = new Date();
                     inicio.setDate(hoje.getDate() - dias);
                     const formatar = (d) => d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                     this.de = formatar(inicio);
                     this.ate = formatar(hoje);
                     this.$nextTick(() => this.buscar(true));
                 },
                 limparBusca() {
                     this.termo = '';
                     this.$refs.q.focus();
                     this.$nextTick(() => this.buscar(true));
                 },
                 limparTudo() {
                     this.termo = '';
                     this.locais = [];
                     this.categorias = [];
                     this.de = '';
                     this.ate = '';
                     this.$nextTick(() => this.buscar(true));
                 }
             }">

        <div class="mx-auto w-full max-w-[1240px] px-4 sm:px-6">

            {{-- Título --}}
            <div class="item-anim flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold sm:text-4xl">Todos os itens perdidos:</h1>
                    <p class="mt-1 text-sm text-[#29166F]/70">Itens encontrados e entregues à DDI.</p>
                </div>

                <p id="resumo" data-atualizar class="rounded-full bg-white/80 px-4 py-1.5 text-sm font-semibold text-[#29166F] ring-1 ring-[#29166F]/15">
                    @if ($itens->total() > 0)
                        Mostrando <strong class="font-extrabold">{{ $itens->firstItem() }}–{{ $itens->lastItem() }}</strong>
                        de <strong class="font-extrabold">{{ $itens->total() }}</strong> {{ $itens->total() === 1 ? 'item' : 'itens' }}
                    @else
                        Nenhum item encontrado
                    @endif
                </p>
            </div>

            <form x-ref="form" method="GET" action="{{ route('itens.index') }}" class="mt-6"
                  @submit.prevent="buscar(true)"
                  @input="buscar()"
                  @change="if ($event.target.name !== 'q') buscar(true)">

                {{-- ============ BARRA DE BUSCA ============ --}}
                <div class="item-anim group flex items-center gap-2 rounded-full border-2 border-[#29166F] bg-white p-1.5 shadow-[0_6px_0_#29166F] ring-4 ring-transparent transition duration-300 focus-within:-translate-y-0.5 focus-within:shadow-[0_8px_0_#29166F] focus-within:ring-[#00988D]/25 sm:gap-3 sm:p-2" style="--i: 1;">

                    {{-- Ícone: vira um "carregando" enquanto busca --}}
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#00988D] text-white transition duration-300 group-focus-within:rotate-6 group-focus-within:scale-105 sm:h-12 sm:w-12">
                        <svg x-show="!carregando" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path>
                        </svg>
                        <svg x-show="carregando" x-cloak class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-dasharray="14 44"></circle>
                        </svg>
                    </span>

                    <label for="q" class="sr-only">Buscar item</label>
                    <input id="q" name="q" type="search" x-ref="q" x-model="termo" value="{{ $termo }}" autocomplete="off" placeholder="Buscar por nome, cor, marca..."
                           class="h-11 min-w-0 flex-1 border-0 bg-transparent px-1 text-base font-medium text-[#29166F] shadow-none placeholder-[#29166F]/40 focus:border-0 focus:outline-none focus:ring-0 sm:h-12 sm:text-lg [&::-webkit-search-cancel-button]:hidden">

                    {{-- Limpar o texto --}}
                    <button type="button" x-show="termo" x-cloak @click="limparBusca()" aria-label="Limpar a busca"
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#29166F]/10 text-[#29166F] transition duration-200 hover:rotate-90 hover:bg-[#29166F] hover:text-white active:scale-90">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"></path></svg>
                    </button>

                    <button type="submit"
                            class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-full bg-[#29166F] px-4 text-sm font-bold text-white transition duration-300 hover:bg-[#1F0F5C] active:scale-95 sm:h-12 sm:px-6">
                        <span class="hidden sm:inline">Buscar</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                    </button>
                </div>

                <p class="item-anim mt-3 hidden items-center gap-2 pl-5 text-xs font-medium text-[#29166F]/60 sm:flex" style="--i: 2;">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#00988D]"></span>
                    Os resultados aparecem enquanto você digita ou escolhe os filtros.
                </p>

                {{-- Botão para abrir os filtros no celular --}}
                <button type="button" @click="filtros = !filtros" :aria-expanded="filtros"
                        class="mt-5 inline-flex h-11 items-center gap-2 rounded-full border-2 border-[#29166F] bg-white px-5 text-sm font-bold text-[#29166F] shadow-[0_3px_0_#29166F] transition duration-200 active:translate-y-0.5 active:shadow-none lg:hidden">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 5h18l-7 8v6l-4-2v-4Z"></path></svg>
                    Filtros
                    <span x-show="total > 0" x-cloak x-text="total" class="flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-[#00988D] px-1.5 text-[11px] font-extrabold text-white"></span>
                </button>

                <div class="mt-6 grid gap-8 lg:grid-cols-[300px_1fr] lg:items-start">

                    {{-- ============ FILTROS ============ --}}
                    <aside class="item-anim overflow-hidden rounded-3xl border-2 border-[#29166F] bg-white shadow-[6px_6px_0_#29166F] lg:sticky lg:top-24 lg:block"
                           :class="filtros ? 'block' : 'hidden'" style="--i: 3;">

                        {{-- Cabeçalho --}}
                        <div class="flex items-center justify-between bg-[#29166F] px-5 py-4 text-white">
                            <h2 class="flex items-center gap-3 text-lg font-extrabold">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#00988D]">
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 5h18l-7 8v6l-4-2v-4Z"></path></svg>
                                </span>
                                Filtros
                            </h2>
                            <span x-show="total > 0" x-cloak x-transition.scale.origin.right
                                  x-text="total + (total === 1 ? ' ativo' : ' ativos')"
                                  class="rounded-full bg-[#00C2B3] px-3 py-1 text-xs font-extrabold text-[#29166F]"></span>
                        </div>

                        <div class="space-y-5 p-5">

                            {{-- Local --}}
                            <div role="group" aria-labelledby="titulo-local">
                                <div class="flex items-center justify-between">
                                    <h3 id="titulo-local" class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-wider text-[#29166F]/70">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-[#EDE8FB] text-[#7B69B8]">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s7-6.5 7-12a7 7 0 1 0-14 0c0 5.5 7 12 7 12Z"></path><circle cx="12" cy="10" r="2.5"></circle></svg>
                                        </span>
                                        Local
                                    </h3>
                                    <span x-show="locais.length" x-cloak x-text="locais.length" class="flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-[#00988D] px-1.5 text-[10px] font-extrabold text-white"></span>
                                </div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($locais as $local)
                                        <label class="cursor-pointer">
                                            <input type="checkbox" name="local[]" value="{{ $local->id }}" x-model="locais" class="sr-only" @checked(in_array($local->id, $locaisSelecionados))>
                                            <span class="chip-filtro">
                                                <svg class="marca h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"></path></svg>
                                                {{ $local->nome }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="border-t-2 border-dashed border-[#29166F]/12"></div>

                            {{-- Categoria --}}
                            <div role="group" aria-labelledby="titulo-categoria">
                                <div class="flex items-center justify-between">
                                    <h3 id="titulo-categoria" class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-wider text-[#29166F]/70">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-[#D6EFEB] text-[#00988D]">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8Z"></path><circle cx="7.5" cy="7.5" r="1.2"></circle></svg>
                                        </span>
                                        Categoria
                                    </h3>
                                    <span x-show="categorias.length" x-cloak x-text="categorias.length" class="flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-[#00988D] px-1.5 text-[10px] font-extrabold text-white"></span>
                                </div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($categorias as $categoria)
                                        <label class="cursor-pointer">
                                            <input type="checkbox" name="categoria[]" value="{{ $categoria->id }}" x-model="categorias" class="sr-only" @checked(in_array($categoria->id, $categoriasSelecionadas))>
                                            <span class="chip-filtro">
                                                <svg class="marca h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"></path></svg>
                                                {{ $categoria->nome }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="border-t-2 border-dashed border-[#29166F]/12"></div>

                            {{-- Data --}}
                            <div role="group" aria-labelledby="titulo-data">
                                <div class="flex items-center justify-between">
                                    <h3 id="titulo-data" class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-wider text-[#29166F]/70">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-[#E7E1F7] text-[#7B69B8]">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2.5"></rect><path d="M3 10h18M8 3v4M16 3v4"></path></svg>
                                        </span>
                                        Data em que foi achado
                                    </h3>
                                    <button type="button" x-show="de || ate" x-cloak @click="de = ''; ate = ''; $nextTick(() => buscar(true))"
                                            class="text-[11px] font-bold text-[#00988D] underline-offset-4 hover:underline">Limpar</button>
                                </div>

                                {{-- Atalhos de período --}}
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ([7 => '7 dias', 30 => '30 dias', 90 => '90 dias'] as $dias => $rotuloPeriodo)
                                        <button type="button" @click="periodo({{ $dias }})"
                                                class="rounded-full bg-[#EDE8FB] px-3 py-1 text-[11px] font-bold text-[#29166F] transition duration-200 hover:-translate-y-0.5 hover:bg-[#29166F] hover:text-white active:scale-95">
                                            {{ $rotuloPeriodo }}
                                        </button>
                                    @endforeach
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-2 lg:grid-cols-1">
                                    <label class="block">
                                        <span class="mb-1 block text-xs font-semibold text-[#29166F]/70">De</span>
                                        <input type="date" name="de" x-model="de" value="{{ $de }}"
                                               class="h-11 w-full rounded-xl border-2 border-[#29166F]/20 bg-white px-3 text-sm font-semibold text-[#29166F] transition duration-200 hover:border-[#00988D] focus:border-[#00988D] focus:ring-4 focus:ring-[#00988D]/15">
                                    </label>
                                    <label class="block">
                                        <span class="mb-1 block text-xs font-semibold text-[#29166F]/70">Até</span>
                                        <input type="date" name="ate" x-model="ate" value="{{ $ate }}"
                                               class="h-11 w-full rounded-xl border-2 border-[#29166F]/20 bg-white px-3 text-sm font-semibold text-[#29166F] transition duration-200 hover:border-[#00988D] focus:border-[#00988D] focus:ring-4 focus:ring-[#00988D]/15">
                                    </label>
                                </div>

                                <div id="erros-data" data-atualizar>
                                    @error('de') <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                                    @error('ate') <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Ações --}}
                        <div class="space-y-3 border-t-2 border-dashed border-[#29166F]/15 bg-[#F7F4FE] p-4">

                            {{-- Só aparece para quem está sem JavaScript --}}
                            <button type="submit"
                                    class="so-sem-js flex h-12 w-full items-center justify-center rounded-full bg-[#29166F] text-sm font-bold text-white shadow-[0_5px_0_#1B0E52] transition duration-200 hover:-translate-y-0.5 hover:bg-[#1F0F5C] active:translate-y-0.5 active:shadow-[0_1px_0_#1B0E52]">
                                Aplicar filtros
                            </button>

                            {{-- No celular: fecha o painel e mostra os resultados --}}
                            <button type="button" @click="filtros = false; $refs.resultados.scrollIntoView({ behavior: 'smooth', block: 'start' })"
                                    class="flex h-12 w-full items-center justify-center rounded-full bg-[#29166F] text-sm font-bold text-white shadow-[0_5px_0_#1B0E52] transition duration-200 active:translate-y-0.5 active:shadow-[0_1px_0_#1B0E52] lg:hidden">
                                Ver resultados
                            </button>

                            <a href="{{ route('itens.index') }}" @click.prevent="limparTudo()"
                               :class="(total > 0 || termo) ? '' : 'pointer-events-none opacity-40'"
                               class="group flex h-11 items-center justify-center gap-2 rounded-full border-2 border-[#29166F]/25 bg-white text-sm font-bold text-[#29166F] transition duration-200 hover:border-[#29166F] hover:bg-[#29166F] hover:text-white active:scale-[0.98]">
                                <svg class="h-4 w-4 transition duration-300 group-hover:-rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8M3 3v5h5"></path></svg>
                                Limpar filtros
                            </a>
                        </div>
                    </aside>

                    {{-- ============ RESULTADOS ============ --}}
                    <div id="resultados" data-atualizar x-ref="resultados" @click="irPagina($event)"
                         :class="carregando ? 'pointer-events-none opacity-50' : ''"
                         class="min-w-0 scroll-mt-28 transition-opacity duration-200">

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
