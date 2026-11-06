@php
    $rotas = \Illuminate\Support\Facades\Route::class;
    $destinoBusca = $rotas::has('itens.index') ? route('itens.index') : '#';
@endphp

<x-layouts.publico titulo="Sumiu&Achou - Reconectando o que foi perdido">

    {{-- Hero --}}
    <section class="mx-auto mt-6 max-w-[1020px] px-4 lg:px-0">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#7B69B8] to-[#6853B0] shadow-[0_14px_30px_-14px_rgba(41,22,111,0.55)] md:grid md:min-h-[200px] md:grid-cols-[460px_1fr]">

            {{-- Foto: public/images/home/hero.(jpg|png|webp) — sugestão: 920x400 px --}}
            <div class="relative hidden md:block">
                <x-foto-slot nome="images/home/hero" alt="Estudantes se ajudando a recuperar um item perdido"
                             class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
                        <div class="absolute -left-12 -top-14 h-52 w-52 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-14 left-20 h-48 w-48 rounded-full bg-[#00988D]/30"></div>
                        <div class="absolute right-28 top-6 h-16 w-16 rounded-full bg-[#29166F]/20"></div>
                        <div class="absolute left-1/2 top-1/2 flex h-28 w-28 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-white/15 ring-8 ring-white/10 backdrop-blur-sm">
                            <svg class="h-14 w-14 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                                <circle cx="10.5" cy="10.5" r="6.5"></circle><path d="m20 20-4.8-4.8"></path>
                            </svg>
                        </div>
                    </div>
                </x-foto-slot>
                {{-- Degradê que funde a foto com o fundo roxo --}}
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-[#7B69B8]/25 via-[#7B69B8]/10 to-[#7B69B8]"></div>
            </div>

            <div class="px-6 py-8 md:flex md:flex-col md:justify-center md:py-0 md:pl-0 md:pr-8">
                <p class="text-[28px] font-normal leading-tight text-white sm:text-[32px]">Reconectando o que</p>
                <p class="mt-1 inline-block w-fit rounded-lg bg-[#29166F] px-3 py-0.5 text-[28px] font-bold text-[#00988D] sm:text-[32px]">
                    FOI PERDIDO!
                </p>
                <p class="mt-3 text-sm leading-snug text-white">
                    Encontre seus itens perdidos ou ajude alguém a recuperar o seu. Um sistema dedicado a
                    reconectar pessoas com aquilo que importa.
                </p>
            </div>
        </div>
    </section>

    {{-- Perdidos recentes --}}
    <section class="mx-auto mt-8 max-w-[1020px] px-4 lg:px-0" x-data>
        <h2 class="text-2xl font-bold">Perdidos recentes:</h2>

        <div class="mt-4 flex items-center gap-2">
            <button type="button" aria-label="Itens anteriores"
                    @click="$refs.trilho.scrollBy({ left: -247, behavior: 'smooth' })"
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#29166F] text-white shadow-md transition hover:bg-[#00988D]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5-7 7 7 7"></path></svg>
            </button>

            <div x-ref="trilho"
                 class="flex flex-1 snap-x gap-3 overflow-x-auto scroll-smooth px-1 pb-4 pt-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @forelse ($itens as $item)
                    @php
                        $linkItem = $rotas::has('itens.show') ? route('itens.show', $item) : '#';
                    @endphp
                    <a href="{{ $linkItem }}"
                       class="group block w-[235px] shrink-0 snap-start rounded-2xl bg-[#D4CDC5] p-2 shadow-[5px_5px_0_#BDB3A8] transition duration-200 hover:-translate-y-1 hover:shadow-[7px_7px_0_#BDB3A8]">
                        <div class="relative aspect-[4/3] overflow-hidden rounded-xl bg-gradient-to-br from-[#EDE8E1] to-[#D6CFC6]">
                            @if ($item->foto)
                                <img src="{{ asset('storage/'.$item->foto) }}" alt="Foto de {{ $item->nome }}"
                                     class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full flex-col items-center justify-center gap-1 text-[#9A8F85]">
                                    <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="4" width="18" height="16" rx="2.5"></rect>
                                        <circle cx="9" cy="10" r="1.5"></circle>
                                        <path d="m21 16-5-5-8 8"></path>
                                    </svg>
                                    <span class="text-[10px] font-medium uppercase tracking-wider">Sem foto</span>
                                </div>
                            @endif

                            <span class="absolute left-2 top-2 rounded-full bg-[#29166F]/90 px-2.5 py-0.5 text-[10px] font-semibold text-white">
                                {{ $item->categoria->nome }}
                            </span>
                        </div>

                        <p class="mt-2.5 truncate text-center text-xs font-semibold">{{ $item->nome }} -{{ $item->data_achado->format('d/m/y') }}</p>
                        <p class="mb-1 flex items-center justify-center gap-1 text-center text-xs">
                            <svg class="h-3 w-3 text-[#00988D]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"></path></svg>
                            <span><strong>Local:</strong> {{ $item->local->nome }}</span>
                        </p>
                    </a>
                @empty
                    <p class="w-full py-10 text-center text-sm">Nenhum item cadastrado por enquanto.</p>
                @endforelse
            </div>

            <button type="button" aria-label="Próximos itens"
                    @click="$refs.trilho.scrollBy({ left: 247, behavior: 'smooth' })"
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#29166F] text-white shadow-md transition hover:bg-[#00988D]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"></path></svg>
            </button>
        </div>
    </section>

    {{-- Busca --}}
    <section class="mx-auto mt-6 max-w-[1020px] px-4 text-center lg:px-0">
        <h2 class="text-base font-bold">Não achou seu item? Procure aqui</h2>

        <form action="{{ $destinoBusca }}" method="GET" class="mx-auto mt-3 flex max-w-[640px] items-center gap-3">
            <label for="q" class="sr-only">Buscar item</label>
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#29166F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path>
                </svg>
                <input id="q" name="q" type="search" placeholder="Buscar item..."
                       class="h-10 w-full rounded-full border border-[#29166F] bg-white pl-10 pr-4 text-xs text-[#29166F] shadow-sm placeholder-[#29166F]/60 focus:border-[#00988D] focus:ring-[#00988D]">
            </div>
            <button type="submit" aria-label="Buscar"
                    class="flex h-9 w-[52px] shrink-0 items-center justify-center rounded-full bg-[#00988D] text-white shadow-md transition hover:brightness-110">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6"></path>
                </svg>
            </button>
        </form>
    </section>

    {{-- Dúvidas --}}
    <section class="mx-auto mb-12 mt-10 max-w-[1020px] px-4 lg:px-0" x-data="{ aberta: null }">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="rounded-[40px] bg-[#29166F] px-8 py-8 md:min-h-[210px] md:flex-1 md:rounded-r-none md:pr-14">
                <h2 class="text-3xl font-bold text-white">Tem alguma dúvida?</h2>

                <div class="mt-4 flex flex-wrap gap-3 md:max-w-[440px]">
                    @foreach ($duvidas as $i => $duvida)
                        <button type="button"
                                @click="aberta = (aberta === {{ $i }} ? null : {{ $i }})"
                                :class="aberta === {{ $i }} ? 'bg-[#B6A9F0] text-[#29166F]' : 'text-[#B6A9F0] hover:bg-[#B6A9F0]/20'"
                                class="rounded-full border border-[#B6A9F0] px-4 py-2 text-left text-[11px] font-bold leading-tight transition md:w-[212px]">
                            {{ $duvida['pergunta'] }}
                        </button>
                    @endforeach
                </div>

                @foreach ($duvidas as $i => $duvida)
                    <p x-show="aberta === {{ $i }}" x-cloak x-transition
                       class="mt-4 text-sm leading-snug text-white/90 md:max-w-[440px]">
                        {{ $duvida['resposta'] }}
                    </p>
                @endforeach
            </div>

            {{-- Foto: public/images/home/duvidas.(jpg|png|webp) — sugestão: 700x450 px --}}
            <div class="relative z-10 mt-4 h-[200px] w-full overflow-hidden rounded-[28px] bg-gradient-to-br from-[#12AFA0] to-[#00796F] shadow-[0_14px_30px_-14px_rgba(0,0,0,0.5)] md:-ml-8 md:mt-0 md:h-[223px] md:w-[347px] md:shrink-0">
                <x-foto-slot nome="images/home/duvidas" alt="Estudante sorrindo, segurando um livro"
                             class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
                        <div class="absolute -right-10 -top-12 h-44 w-44 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-16 -left-8 h-48 w-48 rounded-full bg-[#29166F]/25"></div>
                        <div class="absolute bottom-10 right-8 h-12 w-12 rounded-full bg-white/10"></div>
                        <div class="absolute left-1/2 top-1/2 flex h-24 w-24 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-3xl bg-white/15 ring-8 ring-white/10 backdrop-blur-sm">
                            <svg class="h-12 w-12 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12a8 8 0 0 1-8 8H6l-3 3V12a8 8 0 0 1 8-8h2a8 8 0 0 1 8 8Z"></path>
                                <path d="M10 9.5a2 2 0 1 1 3 1.7c-.7.4-1 .8-1 1.5"></path>
                                <path d="M12 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                </x-foto-slot>
            </div>
        </div>
    </section>

</x-layouts.publico>
