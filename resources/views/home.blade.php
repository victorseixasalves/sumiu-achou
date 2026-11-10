@php
    $rotas = \Illuminate\Support\Facades\Route::class;
    $linkItens = $rotas::has('itens.index') ? route('itens.index') : '#';

    // Ícones dos passos de "Como funciona"
    $iconesPasso = [
        'caixa' => '<path d="M21 8 12 3 3 8v8l9 5 9-5Z"></path><path d="m3 8 9 5 9-5M12 13v8"></path>',
        'lupa'  => '<circle cx="10.5" cy="10.5" r="6.5"></circle><path d="m20 20-4.8-4.8"></path>',
        'chave' => '<circle cx="7.5" cy="15.5" r="4"></circle><path d="m10.5 12.5 9-9M16 7l3 3M13.5 9.5l2.5 2.5"></path>',
    ];
@endphp

<x-layouts.publico titulo="Sumiu&Achou - Reconectando o que foi perdido">

    {{-- ===================== HERO ===================== --}}
    @php
        $palavrasHero = ['Sumiu', 'Achou', 'Reconectou', 'Devolveu'];
        $destinoProcurar = $rotas::has('itens.index') ? route('itens.index') : '#busca';
    @endphp

    <section class="entrada relative overflow-hidden bg-[#7B69B8]">

        {{-- Fundo próprio do hero: faixas de palavras deslizando (cobrem a seção toda, levemente desfocadas) --}}
        <div class="hero-faixas" aria-hidden="true">
            <div class="giro">
                @foreach (range(0, 13) as $faixa)
                    @php
                        $deslocamento = $faixa % 4;
                        $ordem = array_merge(array_slice($palavrasHero, $deslocamento), array_slice($palavrasHero, 0, $deslocamento));
                    @endphp
                    <div class="faixa">
                        <div class="trilha" style="--v: {{ 40 + ($faixa % 5) * 7 }}s;">
                            @foreach (range(1, 4) as $copia)
                                @foreach ($ordem as $palavra)
                                    <span>{{ $palavra }}</span><i></i>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative z-10 mx-auto flex max-w-[1240px] flex-col px-4 sm:px-6 md:min-h-[340px] md:flex-row">

            {{-- O tamanho das letras acompanha a largura da coluna, então "Reconectando o que" nunca quebra de linha --}}
            <div class="relative z-20 order-1 flex min-w-0 flex-1 flex-col justify-center pb-8 pt-10 md:order-2 md:py-14 md:pl-6" style="container-type: inline-size;">

                <h1 class="m-0" style="font-size: clamp(1.5rem, 8.2cqw, 3.4rem);">
                    <span class="entrada block whitespace-nowrap font-medium leading-[1.05] tracking-[-0.02em] text-white" style="--atraso: 150ms;">Reconectando o que</span>
                    <span class="entrada mt-2 block w-fit" style="--atraso: 300ms;">
                        <span class="inline-block -rotate-2 whitespace-nowrap rounded-2xl bg-[#29166F] px-5 py-1 font-extrabold leading-[1.15] tracking-tight text-[#00B8A9] shadow-[5px_5px_0_#1B0E52]">FOI PERDIDO!</span>
                    </span>
                </h1>

                <p class="entrada mt-6 max-w-[480px] text-base leading-[1.7] text-white/90 sm:text-[17px]" style="--atraso: 450ms;">
                    Encontre seus <strong class="font-semibold text-white underline decoration-[#00C2B3] decoration-[3px] underline-offset-[5px]">itens perdidos</strong>
                    ou ajude alguém a <strong class="font-semibold text-white underline decoration-[#00C2B3] decoration-[3px] underline-offset-[5px]">recuperar o seu</strong>.
                    Um sistema dedicado a reconectar pessoas com aquilo que importa.
                </p>

                <div class="entrada mt-8 flex flex-wrap items-center gap-4" style="--atraso: 600ms;">
                    {{-- Botão principal --}}
                    <a href="{{ $destinoProcurar }}"
                       class="group inline-flex h-12 items-center gap-3 rounded-full bg-[#29166F] pl-7 pr-1.5 text-[15px] font-bold text-white shadow-[0_14px_24px_-12px_rgba(27,14,82,0.9)] ring-1 ring-white/10 transition duration-300 hover:-translate-y-0.5 hover:bg-[#1F0F5C] hover:shadow-[0_18px_28px_-12px_rgba(27,14,82,1)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white active:translate-y-0 active:scale-[0.98]">
                        Procurar um item
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#00988D] text-white transition duration-300 group-hover:bg-[#00B8A9]">
                            <svg class="h-4 w-4 transition duration-300 group-hover:-rotate-45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                        </span>
                    </a>

                    {{-- Botão secundário --}}
                    <a href="#como-funciona"
                       class="group inline-flex h-12 items-center gap-2 rounded-full border-2 border-white/70 px-6 text-[15px] font-bold text-white transition duration-300 hover:border-white hover:bg-white hover:text-[#29166F] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white active:scale-[0.98]">
                        Como funciona
                        <svg class="h-4 w-4 transition duration-300 group-hover:translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
                    </a>
                </div>
            </div>

            <div class="pointer-events-none relative order-2 flex items-end justify-center md:order-1 md:w-[52%] md:justify-start">
                <span class="pointer-events-none absolute -bottom-[18%] left-[6%] aspect-square w-[84%] rounded-full bg-[#8A7AC6]" aria-hidden="true"></span>
                <span class="pointer-events-none absolute right-[8%] top-[10%] hidden h-14 w-14 rounded-full bg-[#29166F] md:block" aria-hidden="true"></span>

                <img src="{{ asset('images/home/estudantes-hero.webp') }}" width="1000" height="511"
                     alt="Dois estudantes conversando, um deles devolvendo um celular ao colega"
                     class="entrada relative z-10 block w-full max-w-[700px]" style="--atraso: 200ms;">
            </div>
        </div>
    </section>

    {{-- ===================== PERDIDOS RECENTES ===================== --}}
    <section class="grupo py-12"
             x-data="{
                 inicio: true,
                 fim: false,
                 raf: null,
                 paginas: 1,
                 pagina: 0,
                 medidas() {
                     const t = this.$refs.trilho;
                     const cartoes = t.querySelectorAll('.cartao');
                     const estilo = getComputedStyle(t);
                     return {
                         t: t,
                         total: cartoes.length,
                         passo: cartoes.length ? cartoes[0].offsetWidth + (parseFloat(estilo.columnGap) || 24) : 0,
                         n: Math.max(1, parseInt(estilo.getPropertyValue('--n')) || 1)
                     };
                 },
                 atualizar() {
                     const m = this.medidas();
                     this.inicio = m.t.scrollLeft < 4;
                     this.fim = m.t.scrollLeft + m.t.clientWidth >= m.t.scrollWidth - 4;
                     if (!m.total) return;
                     this.paginas = Math.max(1, Math.ceil(m.total / m.n));
                     this.pagina = this.fim ? this.paginas - 1 : Math.min(this.paginas - 1, Math.round(m.t.scrollLeft / (m.passo * m.n)));
                 },
                 cancelar() {
                     cancelAnimationFrame(this.raf);
                     const t = this.$refs.trilho;
                     t.style.scrollSnapType = '';
                     t.style.scrollBehavior = '';
                 },
                 mover(direcao) {
                     const m = this.medidas();
                     if (m.total <= m.n) return;
                     const atual = Math.round(m.t.scrollLeft / m.passo);
                     const novo = Math.min(Math.max(atual + direcao * m.n, 0), m.total - m.n);
                     this.animar(novo * m.passo);
                 },
                 irPara(p) {
                     const m = this.medidas();
                     if (m.total <= m.n) return;
                     this.animar(Math.min(p * m.n, m.total - m.n) * m.passo);
                 },
                 animar(destino) {
                     const t = this.$refs.trilho;
                     const origem = t.scrollLeft;
                     const delta = destino - origem;
                     if (Math.abs(delta) < 1) return;
                     this.cancelar();
                     const reduzir = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                     const duracao = reduzir ? 0 : Math.min(1000, 600 + Math.abs(delta) * 0.15);
                     t.style.scrollSnapType = 'none';
                     t.style.scrollBehavior = 'auto';
                     const comeco = performance.now();
                     const suavizar = (p) => p < 0.5 ? 4 * p * p * p : 1 - Math.pow(-2 * p + 2, 3) / 2;
                     const quadro = (agora) => {
                         const p = duracao ? Math.min(1, (agora - comeco) / duracao) : 1;
                         t.scrollLeft = origem + delta * suavizar(p);
                         if (p < 1) { this.raf = requestAnimationFrame(quadro); }
                         else { this.cancelar(); this.atualizar(); }
                     };
                     this.raf = requestAnimationFrame(quadro);
                 }
             }"
             x-init="$nextTick(() => atualizar())"
             @resize.window.debounce.150ms="atualizar()">
        <div class="mx-auto w-full max-w-[1240px] px-4 sm:px-6">

            {{-- Título, setas e link --}}
            <div class="item-anim flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold sm:text-3xl">Perdidos recentes:</h2>
                    <p class="mt-1 text-sm text-[#29166F]/70">Itens encontrados e entregues à DDI.</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2" :class="(inicio && fim) ? 'invisible' : ''">
                        <button type="button" aria-label="Itens anteriores" @click="mover(-1)" :disabled="inicio"
                                class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-[#29166F] bg-white text-[#29166F] shadow-[0_3px_0_#29166F] transition duration-200 hover:bg-[#29166F] hover:text-white active:translate-y-0.5 active:shadow-none disabled:pointer-events-none disabled:opacity-35">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5-7 7 7 7"></path></svg>
                        </button>
                        <button type="button" aria-label="Próximos itens" @click="mover(1)" :disabled="fim"
                                class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-[#29166F] bg-white text-[#29166F] shadow-[0_3px_0_#29166F] transition duration-200 hover:bg-[#29166F] hover:text-white active:translate-y-0.5 active:shadow-none disabled:pointer-events-none disabled:opacity-35">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"></path></svg>
                        </button>
                    </div>

                    <a href="{{ $linkItens }}" class="group inline-flex items-center gap-1.5 text-sm font-bold text-[#00988D] transition hover:text-[#29166F]">
                        Ver todos<span class="hidden sm:inline">&nbsp;os itens</span>
                        <svg class="h-4 w-4 transition duration-200 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                    </a>
                </div>
            </div>

            {{-- Cards --}}
            <div x-ref="trilho" @scroll.passive.throttle.80ms="atualizar()" @pointerdown="cancelar()" @wheel.passive="cancelar()" class="carrossel mt-3">
                @forelse ($itens as $item)
                    <x-item-card :item="$item" :indice="$loop->index" class="cartao" />
                @empty
                    <div class="flex w-full flex-col items-center gap-2 rounded-3xl border-2 border-dashed border-[#29166F]/30 bg-white/60 py-12 text-center">
                        <svg class="h-10 w-10 text-[#29166F]/40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8 12 3 3 8v8l9 5 9-5Z"></path><path d="m3 8 9 5 9-5M12 13v8"></path></svg>
                        <p class="text-sm font-semibold">Nenhum item cadastrado por enquanto.</p>
                    </div>
                @endforelse
            </div>

            {{-- Indicador de páginas --}}
            <div x-show="paginas > 1" x-cloak class="flex items-center justify-center gap-2">
                <template x-for="i in paginas" :key="i">
                    <button type="button" @click="irPara(i - 1)" :aria-label="'Ir para a página ' + i"
                            :class="pagina === i - 1 ? 'w-8 bg-[#29166F]' : 'w-2.5 bg-[#29166F]/25 hover:bg-[#29166F]/50'"
                            class="h-2.5 rounded-full transition-all duration-300"></button>
                </template>
            </div>
        </div>
    </section>

    {{-- ===================== BUSCA ===================== --}}
    <section id="busca" class="revelar scroll-mt-24 px-4 py-12 text-center"
             x-data="{
                 q: '',
                 dica: '',
                 exemplos: ['carteira preta', 'mochila azul', 'chaves', 'garrafa térmica', 'fone de ouvido', 'caderno'],
                 digitar() {
                     if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
                     let i = 0, j = 0, apagando = false;
                     const passo = () => {
                         const alvo = this.exemplos[i];
                         if (!apagando) {
                             j++;
                             if (j === alvo.length) { apagando = true; this.dica = alvo; return setTimeout(passo, 1500); }
                         } else {
                             j--;
                             if (j === 0) { apagando = false; i = (i + 1) % this.exemplos.length; }
                         }
                         this.dica = alvo.slice(0, j);
                         setTimeout(passo, apagando ? 35 : 85);
                     };
                     passo();
                 }
             }"
             x-init="digitar()"
             @hashchange.window="if (location.hash === '#busca') $nextTick(() => $refs.q.focus({ preventScroll: true }))">

        <div class="relative mx-auto max-w-[860px]">

            <h2 class="text-[1.65rem] font-extrabold leading-tight text-[#29166F] sm:text-4xl">
                Não achou seu item?
                <span class="relative inline-block text-[#00988D]">
                    Procure aqui
                    <svg class="absolute -bottom-2 left-0 h-2.5 w-full" viewBox="0 0 200 10" preserveAspectRatio="none" aria-hidden="true">
                        <path d="M2 6 Q 26 0 50 6 T 100 6 T 150 6 T 198 6" fill="none" stroke="#29166F" stroke-width="3.2" stroke-linecap="round"></path>
                    </svg>
                </span>
            </h2>
            <p class="mt-4 text-sm text-[#29166F]/75 sm:text-base">Busque pelo nome do objeto, pela categoria ou pelo local onde foi perdido.</p>

            <form action="{{ $linkItens }}" method="GET" class="mt-7">
                <div class="group flex items-center gap-2 rounded-full border-2 border-[#29166F] bg-white p-2 pl-5 shadow-[0_7px_0_#29166F] ring-4 ring-transparent transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_9px_0_#29166F] focus-within:-translate-y-0.5 focus-within:shadow-[0_9px_0_#29166F] focus-within:ring-[#00988D]/25 sm:gap-3 sm:p-2 sm:pl-7">
                    <svg class="h-6 w-6 shrink-0 text-[#00988D] transition duration-300 group-focus-within:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path>
                    </svg>

                    <label for="q" class="sr-only">Buscar item</label>
                    <input id="q" name="q" type="search" x-model="q" x-ref="q" autocomplete="off"
                           :placeholder="dica ? 'Buscar: ' + dica + '|' : 'Buscar item...'"
                           class="h-12 min-w-0 flex-1 border-0 bg-transparent px-0 text-base text-[#29166F] shadow-none placeholder-[#29166F]/45 focus:border-0 focus:outline-none focus:ring-0 sm:h-14 sm:text-lg">

                    <button type="submit" aria-label="Buscar"
                            class="group/botao inline-flex h-12 w-12 shrink-0 items-center justify-center gap-2 rounded-full bg-[#29166F] text-sm font-bold text-white transition duration-300 hover:bg-[#1F0F5C] active:scale-95 sm:h-14 sm:w-auto sm:px-8 sm:text-base">
                        <span class="hidden sm:inline">Buscar</span>
                        <svg class="h-5 w-5 transition duration-300 group-hover/botao:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                    </button>
                </div>
            </form>

            @if ($categorias->isNotEmpty())
                <div class="mt-8 flex flex-wrap items-center justify-center gap-2">
                    <span class="text-xs font-semibold text-[#29166F]/60">Buscas rápidas:</span>
                    @foreach ($categorias as $nomeCategoria)
                        <button type="button" @click="q = @js($nomeCategoria); $refs.q.focus()"
                                class="rounded-full border border-[#29166F]/30 bg-white/70 px-4 py-1.5 text-xs font-semibold text-[#29166F] transition duration-200 hover:-translate-y-0.5 hover:border-[#00988D] hover:bg-[#00988D] hover:text-white active:scale-95">
                            {{ $nomeCategoria }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ===================== COMO FUNCIONA ===================== --}}
    <section id="como-funciona" class="grupo scroll-mt-20 py-12">
        <div class="mx-auto w-full max-w-[1240px] px-4 sm:px-6">

            <div class="item-anim">
                <h2 class="text-2xl font-bold sm:text-3xl">Como funciona:</h2>
                <p class="mt-1 text-sm">Do achado à devolução, em 3 passos.</p>
            </div>

            <ol class="mt-12 grid gap-10 md:grid-cols-3 md:gap-6">
                @foreach ($passos as $i => $passo)
                    <li style="--i: {{ $i }};"
                        class="item-anim group relative rounded-3xl border border-[#E4DEF5] bg-[#F9F8FE] p-7 transition duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[#29166F]/10">
                        <span class="absolute -top-4 left-7 rounded-full bg-[#00988D] px-3.5 py-1 text-xs font-bold text-white shadow-md">Passo {{ $i + 1 }}</span>

                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#29166F] text-white shadow-md transition duration-300 group-hover:rotate-6 group-hover:bg-[#00988D]">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $iconesPasso[$passo['icone']] !!}</svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold leading-snug">{{ $passo['titulo'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-[#29166F]/80">{{ $passo['texto'] }}</p>

                        @if (! $loop->last)
                            <span class="absolute -right-[30px] top-1/2 z-10 hidden h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white text-[#00988D] shadow-md ring-1 ring-[#E4DEF5] md:flex" aria-hidden="true">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                            </span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ===================== ONDE FICA A DDI ===================== --}}
    @php
        // Ponto da DDI no Google Maps (mesma localização da Rádio Universitária Unimontes 101,1 FM)
        $ddiCoordenadas = '-16.719476,-43.8799577';
        $mapaEmbed = 'https://maps.google.com/maps?q='.$ddiCoordenadas.'&z=18&hl=pt-BR&output=embed';
        $mapaRota  = 'https://www.google.com/maps/dir/?api=1&destination='.$ddiCoordenadas;

        // Paradas do trajeto (mini cards em formato de pílula)
        $paradas = [
            ['Campus da Unimontes', '<path d="M12 22s7-6.5 7-12a7 7 0 1 0-14 0c0 5.5 7 12 7 12Z"></path><circle cx="12" cy="10" r="2.5"></circle>'],
            ['Ao lado da biblioteca', '<path d="M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2Z"></path><path d="M4 19V5M9 7h6"></path>'],
            ['Embaixo da COTEPS', '<path d="M12 4v13M6 12l6 6 6-6"></path>'],
        ];
    @endphp

    <section id="onde-fica" class="grupo scroll-mt-20 py-10">
        <div class="mx-auto w-full max-w-[1240px] px-4 sm:px-6">
            <div class="revelar relative md:my-8">

                {{-- Card verde: cantos assimétricos, mapa à esquerda (mais alto que o card) e informações à direita --}}
                <div class="relative flex flex-col rounded-[28px] rounded-tr-[64px] bg-[#00988D] p-6 shadow-[0_8px_0_#006F67] sm:p-8 md:block md:rounded-[32px] md:rounded-tr-[110px] md:py-11 md:pl-[52%] md:pr-11">

                    {{-- Caminhos pontilhados do fundo (só de enfeite): descem pela lateral direita até o destino --}}
                    <div class="pointer-events-none absolute inset-0 hidden overflow-hidden rounded-[inherit] md:block" aria-hidden="true">
                        <svg class="absolute inset-0 h-full w-full" viewBox="0 0 1000 600" preserveAspectRatio="none" fill="none">
                            <path class="rota-pontilhada" d="M985 -20 C 930 120, 1010 230, 962 340 S 925 500, 988 640"
                                  stroke="rgba(255,255,255,.55)" stroke-width="3.5" stroke-linecap="round" stroke-dasharray="1 13" vector-effect="non-scaling-stroke"></path>
                            <path class="rota-pontilhada" d="M-20 600 C 120 540, 200 640, 340 585 S 470 560, 520 600"
                                  stroke="rgba(255,255,255,.4)" stroke-width="3.5" stroke-linecap="round" stroke-dasharray="1 13" vector-effect="non-scaling-stroke"></path>
                        </svg>
                    </div>

                    <div class="relative">
                        <h2 class="item-anim text-3xl font-extrabold text-white sm:text-4xl lg:text-[2.6rem]">Onde fica a <span class="text-[#29166F]">DDI?</span></h2>
                        <p class="item-anim mt-3 max-w-[480px] text-sm leading-relaxed text-white/90 sm:text-base" style="--i: 1;">
                            A DDI fica no <strong class="font-extrabold text-white">Prédio 4</strong> do campus da Unimontes, em Montes Claros (MG).
                            É lá que se entrega um item encontrado e onde você retira o seu, depois que a devolução é aprovada.
                        </p>

                        {{-- Trajeto: paradas ligadas por uma linha pontilhada que "anda" até a DDI --}}
                        <ul class="relative mt-8 space-y-5">
                            <svg class="pointer-events-none absolute bottom-6 left-[22px] top-6 h-[calc(100%-3rem)] w-1" viewBox="0 0 2 100" preserveAspectRatio="none" fill="none" aria-hidden="true">
                                <path class="rota-pontilhada" d="M1 0 V100" stroke="rgba(255,255,255,.95)" stroke-width="3.5" stroke-linecap="round" stroke-dasharray="1 11" vector-effect="non-scaling-stroke"></path>
                            </svg>

                            @foreach ($paradas as $k => [$referencia, $iconeReferencia])
                                <li class="item-anim relative flex h-12 w-fit items-center gap-3 rounded-full bg-white pl-1.5 pr-6 shadow-[0_4px_0_#006F67] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_6px_0_#006F67]" style="--i: {{ $k + 2 }};">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#00988D] text-white">
                                        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $iconeReferencia !!}</svg>
                                    </span>
                                    <span class="text-sm font-bold text-[#29166F]">{{ $referencia }}</span>
                                </li>
                            @endforeach

                            {{-- Destino --}}
                            <li class="item-anim relative flex h-12 w-fit items-center gap-3 rounded-full bg-[#29166F] pl-1.5 pr-3 shadow-[0_4px_0_#1B0E52]" style="--i: 5;">
                                <span class="bolha flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#00988D] text-white">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"></path></svg>
                                </span>
                                <span class="text-sm font-extrabold text-white">DDI</span>
                                <span class="rounded-full bg-[#00C2B3] px-3 py-1 text-xs font-extrabold uppercase tracking-wide text-[#29166F]">Prédio 4</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Mapa à esquerda: mais alto que o card, saindo um pouco por cima e por baixo (no celular vem primeiro) --}}
                    <div class="item-anim relative order-first mb-9 md:absolute md:-inset-y-8 md:left-8 md:order-none md:mb-0 md:w-[45%]" style="--i: 3;">
                        <div class="relative h-72 overflow-hidden rounded-3xl border-4 border-white bg-white shadow-[0_8px_0_#006F67] sm:h-80 md:h-full"
                             x-data="{ ativo: false }" @mouseleave="ativo = false">
                            <iframe src="{{ $mapaEmbed }}"
                                    title="Mapa com a localização da DDI (Prédio 4), embaixo da COTEPS e ao lado da biblioteca, no campus da Unimontes"
                                    class="block h-full w-full border-0"
                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>

                            <span class="pointer-events-none absolute bottom-3 left-3 inline-flex items-center gap-2 rounded-full bg-[#29166F] px-3.5 py-1.5 text-xs font-bold text-white shadow-md">
                                <span class="h-2 w-2 rounded-full bg-[#00C2B3]"></span>
                                DDI · Prédio 4
                            </span>

                            {{-- Trava a rolagem do mapa até o clique (evita "prender" a página ao rolar) --}}
                            <button type="button" x-show="!ativo" @click="ativo = true" aria-label="Clique para mover e ampliar o mapa"
                                    class="absolute inset-0 flex cursor-pointer items-start justify-center bg-transparent pt-4 opacity-0 transition duration-200 hover:opacity-100 focus-visible:opacity-100">
                                <span class="rounded-full bg-[#29166F]/90 px-4 py-2 text-xs font-bold text-white shadow-lg">Clique para mover o mapa</span>
                            </button>

                            {{-- Botão sobre o mapa --}}
                            <a href="{{ $mapaRota }}" target="_blank" rel="noopener"
                               class="group absolute bottom-3 right-3 z-10 inline-flex h-10 items-center gap-2 rounded-full bg-white pl-4 pr-1.5 text-[13px] font-bold text-[#29166F] shadow-[0_3px_0_#006F67] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_5px_0_#006F67] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#29166F] active:translate-y-0.5 active:shadow-none">
                                Como chegar
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#29166F] text-white transition duration-300 group-hover:bg-[#00988D]">
                                    <svg class="h-3.5 w-3.5 transition duration-300 group-hover:-rotate-45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                                </span>
                            </a>
                        </div>

                        {{-- Pino do canto do mapa, com um anel pontilhado girando ao redor --}}
                        <div class="bolha pointer-events-none absolute -right-3 -top-4 z-10 h-[4.5rem] w-[4.5rem] md:-right-6 md:-top-5" aria-hidden="true">
                            <svg class="anel-pontilhado absolute inset-0 h-full w-full" viewBox="0 0 72 72" fill="none">
                                <circle cx="36" cy="36" r="34" stroke="rgba(255,255,255,.95)" stroke-width="2.6" stroke-linecap="round" stroke-dasharray="1 8"></circle>
                            </svg>
                            <div class="absolute inset-2.5 flex items-center justify-center rounded-full bg-[#29166F] text-white shadow-lg ring-4 ring-[#00988D]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s7-6.5 7-12a7 7 0 1 0-14 0c0 5.5 7 12 7 12Z"></path><circle cx="12" cy="10" r="2.5"></circle>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== DÚVIDAS ===================== --}}
    <section class="grupo py-16"
             x-data="{
                 duvidas: @js($duvidas),
                 visivel: false,
                 atual: 0,
                 trocando: false,
                 origem: null,
                 abrir(i, el) {
                     this.atual = i;
                     this.origem = el;
                     this.visivel = true;
                     document.body.style.overflow = 'hidden';
                     this.$nextTick(() => this.$refs.fechar.focus());
                 },
                 fechar() {
                     if (!this.visivel) return;
                     this.visivel = false;
                     document.body.style.overflow = '';
                     if (this.origem) this.origem.focus();
                 },
                 ir(i) {
                     const total = this.duvidas.length;
                     const novo = (i + total) % total;
                     if (novo === this.atual) return;
                     this.trocando = true;
                     setTimeout(() => { this.atual = novo; this.trocando = false; }, 160);
                 },
                 prender(e) {
                     const itens = [...this.$refs.painel.querySelectorAll('button')];
                     if (!itens.length) return;
                     const primeiro = itens[0];
                     const ultimo = itens[itens.length - 1];
                     if (e.shiftKey && document.activeElement === primeiro) { e.preventDefault(); ultimo.focus(); }
                     else if (!e.shiftKey && document.activeElement === ultimo) { e.preventDefault(); primeiro.focus(); }
                 }
             }"
             @keydown.escape.window="fechar()">

        <div class="mx-auto w-full max-w-[1240px] px-4 sm:px-6">

            {{-- O card verde é mais alto que o roxo e passa por cima da ponta dele, com um encaixe curvo nos cantos --}}
            <div class="revelar relative md:py-[38px]">

                {{-- Card roxo --}}
                <div class="relative overflow-hidden rounded-[36px] bg-[#29166F] px-5 pb-12 pt-9 sm:px-8 md:min-h-[310px] md:rounded-[48px] md:py-11 md:pl-10 md:pr-[340px] lg:pr-[390px] xl:pr-[450px]">

                    {{-- "?" gigante vazado, só de enfeite (mesmo estilo das palavras do hero) --}}
                    <span class="pointer-events-none absolute -bottom-14 -left-2 hidden select-none text-[15rem] font-extrabold leading-none md:block"
                          style="color: transparent; -webkit-text-stroke: 2px rgba(255, 255, 255, .09);" aria-hidden="true">?</span>

                    <div class="relative">
                        @php
                            // Ícone de cada pergunta (mesma ordem das dúvidas)
                            $iconesDuvida = [
                                '<path d="M12 22s7-6.5 7-12a7 7 0 1 0-14 0c0 5.5 7 12 7 12Z"></path><circle cx="12" cy="10" r="2.5"></circle>',
                                '<path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1Z"></path><path d="M15.5 8.5a5 5 0 0 1 0 7M18.5 6a9 9 0 0 1 0 12"></path>',
                                '<circle cx="7.5" cy="15.5" r="4"></circle><path d="m10.5 12.5 9-9M16 7l3 3M13.5 9.5l2.5 2.5"></path>',
                                '<path d="M21 8 12 3 3 8v8l9 5 9-5Z"></path><path d="m3 8 9 5 9-5M12 13v8"></path>',
                            ];
                        @endphp

                        <h2 class="text-3xl font-extrabold text-white sm:text-4xl">Tem alguma <span class="text-[#00C2B3]">dúvida?</span></h2>
                        <p class="mt-2 text-sm text-white/70">Toque em uma pergunta para ver a resposta.</p>

                        <div class="mt-6 grid auto-rows-fr gap-4 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2">
                            @foreach ($duvidas as $i => $duvida)
                                <button type="button" style="--i: {{ $i }};"
                                        @click="abrir({{ $i }}, $el)"
                                        aria-haspopup="dialog"
                                        class="item-anim group relative flex min-h-[76px] items-center gap-3 rounded-2xl bg-[#EDE8FB] p-3 pr-4 text-left shadow-[0_5px_0_#1B0E52] transition duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-[0_8px_0_#1B0E52] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#00C2B3] active:translate-y-0.5 active:shadow-[0_1px_0_#1B0E52]">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#00988D] text-white transition duration-300 group-hover:rotate-6 group-hover:scale-110">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $iconesDuvida[$i] ?? $iconesDuvida[0] !!}</svg>
                                    </span>
                                    <span class="flex-1 text-[13px] font-bold leading-snug text-[#29166F]">{{ $duvida['pergunta'] }}</span>
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#29166F] text-white transition duration-300 group-hover:bg-[#00988D]">
                                        <svg class="h-3.5 w-3.5 transition duration-300 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                                    </span>
                                </button>
                            @endforeach
                        </div>

                        <a href="#como-funciona" class="group mt-7 inline-flex flex-wrap items-center gap-x-2 text-sm font-semibold text-[#B6A9F0] transition duration-200 hover:text-white">
                            Quer ver o passo a passo completo?
                            <span class="underline decoration-[#00C2B3] decoration-2 underline-offset-4">Como funciona</span>
                            <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                        </a>
                    </div>
                </div>

                {{-- Card verde com a estudante (a cabeça passa um pouco da borda de cima) --}}
                <div class="encaixe-verde relative z-10 mx-5 -mt-10 md:absolute md:inset-y-0 md:right-0 md:m-0 md:w-[300px] lg:w-[340px] xl:w-[400px]">
                    <div class="relative h-52 overflow-hidden rounded-[22px] bg-[#00988D] shadow-[0_18px_34px_-16px_rgba(0,0,0,0.5)] sm:h-60 md:h-full">
                        <span class="absolute -right-10 -bottom-[18%] aspect-square w-[85%] rounded-full bg-[#12AFA0]" aria-hidden="true"></span>
                        <span class="absolute -left-12 top-[8%] aspect-square w-[40%] rounded-full bg-[#00877D]" aria-hidden="true"></span>
                        <span class="absolute right-5 top-5 h-9 w-9 rounded-full bg-[#29166F]" aria-hidden="true"></span>
                    </div>

                    <img src="{{ asset('images/home/estudante-duvidas.webp') }}" width="700" height="884"
                         alt="Estudante sorrindo, segurando um livro"
                         class="pointer-events-none absolute bottom-0 left-1/2 h-[112%] max-w-[112%] -translate-x-1/2 object-contain object-bottom">

                    {{-- Balão de pergunta flutuando perto da estudante --}}
                    <div class="bolha pointer-events-none absolute -left-3 top-[20%] md:-left-5" aria-hidden="true">
                        <div class="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[#00988D] shadow-lg">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.2 9a3 3 0 1 1 4.6 2.5c-1 .6-1.8 1.2-1.8 2.5"></path><path d="M12 17.5h.01"></path>
                            </svg>
                            <span class="absolute -bottom-1.5 left-4 h-3.5 w-3.5 rotate-45 rounded-sm bg-white"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Janela (pop-up) com a resposta --}}
        <div class="modal-duvida" :class="{ aberto: visivel }" :aria-hidden="!visivel" @keydown.tab="prender($event)">
            <div class="modal-fundo" @click="fechar()"></div>

            <div x-ref="painel" role="dialog" aria-modal="true" aria-labelledby="duvida-titulo" class="painel-modal">
                <div class="relative rounded-[32px] bg-[#F5FAFF] shadow-[0_34px_70px_-22px_rgba(0,0,0,0.65)]">

                    {{-- Cabeçalho --}}
                    <div class="relative rounded-t-[32px] bg-[#29166F] px-6 pb-10 pt-6 sm:px-8">
                        <div class="relative flex items-center justify-between gap-4">
                            <span class="inline-block rounded-full bg-[#00988D] px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-white">Dúvida</span>
                            <button type="button" x-ref="fechar" @click="fechar()" aria-label="Fechar"
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/10 text-white transition duration-300 hover:rotate-90 hover:bg-[#00988D] active:scale-90">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"></path></svg>
                            </button>
                        </div>

                        <h3 id="duvida-titulo"
                            class="relative mt-3 pr-10 text-xl font-bold leading-snug text-white transition duration-200 sm:text-2xl"
                            :class="trocando ? 'translate-y-2 opacity-0' : ''"
                            x-text="duvidas[atual].pergunta"></h3>

                        <div class="absolute -bottom-7 right-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#00988D] text-white shadow-lg ring-4 ring-[#F5FAFF] sm:right-8" aria-hidden="true">
                            <svg class="balancar h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12a8 8 0 0 1-8 8H6l-3 3V12a8 8 0 0 1 8-8h2a8 8 0 0 1 8 8Z"></path>
                                <path d="M10 9.5a2 2 0 1 1 3 1.7c-.7.4-1 .8-1 1.5"></path>
                                <path d="M12 16h.01"></path>
                            </svg>
                        </div>
                    </div>

                    {{-- Resposta --}}
                    <div class="px-6 pb-2 pt-10 sm:px-8">
                        <p class="text-[15px] leading-relaxed text-[#29166F] transition duration-200"
                           :class="trocando ? 'translate-y-2 opacity-0' : ''"
                           x-text="duvidas[atual].resposta"></p>
                    </div>

                    {{-- Rodapé --}}
                    <div class="flex items-center justify-between gap-3 px-6 pb-6 pt-5 sm:px-8">
                        <div class="flex items-center gap-2">
                            <button type="button" @click="ir(atual - 1)" aria-label="Dúvida anterior"
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-[#29166F] text-white shadow-md transition duration-200 hover:scale-110 hover:bg-[#00988D] active:scale-95">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5-7 7 7 7"></path></svg>
                            </button>
                            <span class="min-w-[3.2rem] text-center text-xs font-semibold text-[#29166F]/70" x-text="(atual + 1) + ' de ' + duvidas.length"></span>
                            <button type="button" @click="ir(atual + 1)" aria-label="Próxima dúvida"
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-[#29166F] text-white shadow-md transition duration-200 hover:scale-110 hover:bg-[#00988D] active:scale-95">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"></path></svg>
                            </button>
                        </div>

                        <button type="button" @click="fechar()"
                                class="rounded-full bg-[#00988D] px-7 py-2.5 text-xs font-bold uppercase tracking-wide text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:brightness-110 active:scale-95">
                            Entendi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.publico>
