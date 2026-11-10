@props(['titulo' => 'Sumiu&Achou'])

@php
    $rotas = \Illuminate\Support\Facades\Route::class;
    $linkItens = $rotas::has('itens.index') ? route('itens.index') : '#';
    $linkForum = $rotas::has('forum.index') ? route('forum.index') : '#';
    $ativoItens = request()->routeIs('itens.*');
    $ativoForum = request()->routeIs('forum.*');

    // Fundo animado: objetos de "achados e perdidos" espalhados pela tela, se movendo de leve.
    $iconesFundo = ['chave', 'carteira', 'mochila', 'celular', 'oculos', 'guardachuva', 'cracha', 'fone', 'livro', 'garrafa', 'caderno', 'laptop', 'pin', 'lupa'];
    $coresFundo = ['#7B69B8', '#00988D', '#29166F'];
    $colunas = 8;
    $linhas = 4;
    $objetos = [];
    $n = 0;
    mt_srand(11); // posições fixas (sempre o mesmo desenho)
    for ($l = 0; $l < $linhas; $l++) {
        for ($c = 0; $c < $colunas; $c++) {
            $cor = $coresFundo[$n % 3];
            $objetos[] = [
                'icone' => $iconesFundo[($n * 5) % count($iconesFundo)],
                'x' => round((($c + 0.1 + mt_rand(0, 80) / 100) / $colunas) * 100, 1),
                'y' => round((($l + 0.1 + mt_rand(0, 80) / 100) / $linhas) * 100, 1),
                'tam' => mt_rand(34, 58),
                'rot' => mt_rand(-28, 28),
                'dx' => mt_rand(-34, 34),                              // deslocamento horizontal (px)
                'dy' => mt_rand(-38, 38),                              // deslocamento vertical (px)
                'dr' => (mt_rand(0, 1) ? 1 : -1) * mt_rand(10, 22),    // giro extra (graus)
                'sc' => mt_rand(108, 116) / 100,                       // "respira" um pouco maior
                'sc2' => mt_rand(86, 94) / 100,                        // e um pouco menor
                'dur' => mt_rand(8, 14),                               // duração do movimento (s)
                'atraso' => mt_rand(0, 14),
                'dc' => mt_rand(18, 32),                               // duração do ciclo de cores (s)
                'ac' => mt_rand(0, 32),                                // fase do ciclo de cores (s)
                'cor' => $cor,
                'op' => ($cor === '#29166F' ? mt_rand(12, 17) : mt_rand(22, 30)) / 100,
                'mobile' => $n % 2 === 0,
            ];
            $n++;
        }
    }
@endphp
<!DOCTYPE html>
<html lang="pt-BR" style="scroll-behavior: smooth;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#29166F">
    <title>{{ $titulo }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet">

    {{-- Marca que o JavaScript está ativo (as animações de entrada só se escondem com JS). --}}
    <script>document.documentElement.classList.add('js');</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        html { scrollbar-gutter: stable; }

        /* ---------- Animações ---------- */
        @keyframes subir    { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: none; } }
        @keyframes girar { to { transform: rotate(360deg); } }
        @keyframes rota { to { stroke-dashoffset: -150; } }
        @keyframes bolha { 0%, 100% { transform: translateY(0) rotate(-5deg); } 50% { transform: translateY(-9px) rotate(4deg); } }
        @keyframes balancar { 0%, 100% { transform: rotate(-10deg); } 50% { transform: rotate(10deg); } }
        @keyframes oscilar  {
            0%, 100% { transform: translate3d(0, 0, 0) rotate(var(--r)) scale(1); }
            25%      { transform: translate3d(var(--dx), calc(var(--dy) * .4), 0) rotate(calc(var(--r) + var(--dr) * .6)) scale(var(--sc)); }
            50%      { transform: translate3d(calc(var(--dx) * .4), var(--dy), 0) rotate(calc(var(--r) + var(--dr))) scale(1); }
            75%      { transform: translate3d(calc(var(--dx) * -.8), calc(var(--dy) * .5), 0) rotate(calc(var(--r) - var(--dr) * .6)) scale(var(--sc2)); }
        }
        /* Troca de cor com as cores do sistema (roxo, verde, lilás, roxo-escuro e verde-claro) */
        @keyframes cores {
            0%, 100% { color: #7B69B8; opacity: .28; }
            20%      { color: #00988D; opacity: .30; }
            40%      { color: #B6A9F0; opacity: .40; }
            60%      { color: #29166F; opacity: .16; }
            80%      { color: #00C2B3; opacity: .26; }
        }

        .entrada  { animation: subir .75s cubic-bezier(.2, .7, .2, 1) both; animation-delay: var(--atraso, 0ms); }
        .anel-pontilhado { animation: girar 24s linear infinite; transform-origin: 50% 50%; }
        .rota-pontilhada { animation: rota 14s linear infinite; }
        .bolha    { animation: bolha 4.5s ease-in-out infinite; }
        .balancar { animation: balancar 3.2s ease-in-out infinite; transform-origin: 50% 50%; }

        /* Revelar ao rolar a página */
        .js .revelar { opacity: 0; transform: translateY(26px); transition: opacity .8s ease, transform .8s cubic-bezier(.2, .7, .2, 1); transition-delay: var(--atraso, 0ms); }
        .js .revelar.revelado { opacity: 1; transform: none; }

        /* Itens que entram um após o outro quando o grupo aparece */
        .js .grupo:not(.revelado) .item-anim { opacity: 0; }
        .grupo.revelado .item-anim { animation: subir .65s cubic-bezier(.2, .7, .2, 1) backwards; animation-delay: calc(var(--i, 0) * 90ms + 120ms); }

        /* ---------- Fundo animado ---------- */
        .fundo-objetos { position: fixed; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; }
        .fundo-objetos .objeto {
            position: absolute; left: var(--x); top: var(--y); width: var(--s); height: var(--s);
            color: var(--c); opacity: var(--o); will-change: transform;
            animation: oscilar var(--d) ease-in-out var(--a) infinite,
                       cores var(--dc) ease-in-out var(--ac) infinite;
        }

        /* ---------- Fundo do hero: faixas de palavras deslizando (cobrem tudo, desfoque leve) ---------- */
        .hero-faixas { position: absolute; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; }
        .hero-faixas .giro {
            position: absolute; left: -15%; right: -15%; top: 50%;
            display: flex; flex-direction: column; gap: .12em;
            font-size: clamp(3.2rem, 7vw, 6rem);
            transform: translateY(-50%) rotate(-6deg);
            filter: blur(1.6px);
        }
        .hero-faixas .faixa { overflow: hidden; white-space: nowrap; font-weight: 800; line-height: 1; text-transform: uppercase; }
        .hero-faixas .faixa:nth-child(odd)  { color: transparent; -webkit-text-stroke: 1.5px rgba(255, 255, 255, .28); }
        .hero-faixas .faixa:nth-child(even) { color: rgba(255, 255, 255, .08); }
        .hero-faixas .trilha { display: inline-flex; align-items: center; will-change: transform; animation: deslizar var(--v, 48s) linear infinite; }
        .hero-faixas .faixa:nth-child(even) .trilha { animation-direction: reverse; }
        .hero-faixas .trilha span { margin-right: .35em; }
        .hero-faixas .trilha i { display: inline-block; width: .28em; height: .28em; border-radius: 50%; background: rgba(255, 255, 255, .2); margin-right: .35em; }
        /* A trilha tem 4 cópias da sequência e anda exatamente uma cópia (25%), então nunca fica vazio nem "pula" */
        @keyframes deslizar { from { transform: translateX(0); } to { transform: translateX(-25%); } }

        /* ---------- Carrossel: sempre mostra um número inteiro de cards ---------- */
        .carrossel { --n: 1; --gap: 1.5rem; display: flex; gap: var(--gap); overflow-x: auto; scroll-snap-type: x mandatory;
                     padding: .75rem .75rem 1.5rem; scroll-padding-inline: .75rem; scrollbar-width: none; -webkit-overflow-scrolling: touch; }
        .carrossel::-webkit-scrollbar { display: none; }
        .carrossel > .cartao { flex: 0 0 calc((100% - (var(--n) - 1) * var(--gap)) / var(--n)); min-width: 0; scroll-snap-align: start; }
        .carrossel > :first-child { margin-left: auto; }
        .carrossel > :last-child { margin-right: auto; }
        @media (min-width: 540px)  { .carrossel { --n: 2; } }
        @media (min-width: 780px)  { .carrossel { --n: 3; } }
        @media (min-width: 1000px) { .carrossel { --n: 4; } }
        @media (min-width: 1300px) { .carrossel { --n: 5; } }

        /* ---------- Encaixe curvo entre o card roxo e o verde (que é mais alto) ---------- */
        @media (min-width: 768px) {
            .encaixe-verde::before, .encaixe-verde::after { content: ""; position: absolute; left: -16px; width: 16px; height: 16px; pointer-events: none; }
            .encaixe-verde::before { top: 22px;    background: radial-gradient(circle at 0 0,    transparent 16px, #29166F 16.5px); }
            .encaixe-verde::after  { bottom: 22px; background: radial-gradient(circle at 0 100%, transparent 16px, #29166F 16.5px); }
        }

        /* ---------- Janela (pop-up) das dúvidas ---------- */
        .modal-duvida { position: fixed; inset: 0; z-index: 60; display: flex; align-items: center; justify-content: center; padding: 1rem;
                        visibility: hidden; pointer-events: none; transition: visibility 0s linear .4s; }
        .modal-duvida.aberto { visibility: visible; pointer-events: auto; transition-delay: 0s; }
        .modal-fundo { position: absolute; inset: 0; background: rgba(27, 14, 82, .72); -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px);
                       opacity: 0; transition: opacity .4s ease; }
        .modal-duvida.aberto .modal-fundo { opacity: 1; }
        .painel-modal { position: relative; width: 100%; max-width: 540px; opacity: 0; transform: translateY(32px) scale(.94);
                        transition: opacity .35s ease, transform .5s cubic-bezier(.2, .9, .25, 1.12); }
        .modal-duvida.aberto .painel-modal { opacity: 1; transform: none; transition-delay: .05s; }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto !important; }
            .carrossel { scroll-behavior: auto; }
            .entrada, .balancar, .bolha, .rota-pontilhada, .anel-pontilhado, .fundo-objetos .objeto, .hero-faixas .trilha, .grupo.revelado .item-anim { animation: none !important; }
            .js .revelar, .js .grupo:not(.revelado) .item-anim { opacity: 1 !important; transform: none !important; transition: none !important; }
            .modal-duvida, .modal-fundo, .painel-modal { transition: none !important; }
        }
    </style>
</head>
<body class="flex min-h-screen flex-col bg-[#F5FAFF] text-[#29166F] antialiased"
      style="font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;">

    {{-- Fundo animado --}}
    <div class="fundo-objetos" aria-hidden="true">
        <svg width="0" height="0" style="position: absolute;">
            <defs>
                <symbol id="i-chave" viewBox="0 0 24 24"><circle cx="7.5" cy="15.5" r="4"></circle><path d="m10.5 12.5 9-9M16 7l3 3M13.5 9.5l2.5 2.5"></path></symbol>
                <symbol id="i-lupa" viewBox="0 0 24 24"><circle cx="10.5" cy="10.5" r="6.5"></circle><path d="m20 20-4.8-4.8"></path></symbol>
                <symbol id="i-carteira" viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="13" rx="2.5"></rect><path d="M3 10h18M16 14.5h2M5 6l11-3 2 3"></path></symbol>
                <symbol id="i-mochila" viewBox="0 0 24 24"><path d="M9 6a3 3 0 0 1 6 0"></path><rect x="5" y="6" width="14" height="15" rx="4"></rect><path d="M9 15h6M9 11.5h6"></path></symbol>
                <symbol id="i-celular" viewBox="0 0 24 24"><rect x="7" y="2.5" width="10" height="19" rx="2.5"></rect><path d="M11 18.5h2"></path></symbol>
                <symbol id="i-oculos" viewBox="0 0 24 24"><circle cx="6.5" cy="14" r="3.5"></circle><circle cx="17.5" cy="14" r="3.5"></circle><path d="M10 14h4M3.2 12.5 5 7M20.8 12.5 19 7"></path></symbol>
                <symbol id="i-guardachuva" viewBox="0 0 24 24"><path d="M3 12a9 9 0 0 1 18 0Z"></path><path d="M12 12v6.5a2 2 0 0 0 4 0M12 3V2"></path></symbol>
                <symbol id="i-cracha" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2.5"></rect><circle cx="9" cy="11" r="2"></circle><path d="M6.5 16c.5-1.5 1.5-2 2.5-2s2 .5 2.5 2M14 10h4M14 13h3"></path></symbol>
                <symbol id="i-fone" viewBox="0 0 24 24"><path d="M4 15v-3a8 8 0 0 1 16 0v3"></path><rect x="3" y="14" width="4" height="6" rx="1.5"></rect><rect x="17" y="14" width="4" height="6" rx="1.5"></rect></symbol>
                <symbol id="i-pin" viewBox="0 0 24 24"><path d="M12 22s7-6.5 7-12a7 7 0 1 0-14 0c0 5.5 7 12 7 12Z"></path><circle cx="12" cy="10" r="2.5"></circle></symbol>
                <symbol id="i-livro" viewBox="0 0 24 24"><path d="M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2Z"></path><path d="M4 19V5M9 7h6"></path></symbol>
                <symbol id="i-garrafa" viewBox="0 0 24 24"><path d="M9 2h6M10 2v3M14 2v3"></path><path d="M8 8a2 2 0 0 1 2-3h4a2 2 0 0 1 2 3v12a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2Z"></path><path d="M8 12h8"></path></symbol>
                <symbol id="i-caderno" viewBox="0 0 24 24"><rect x="5" y="3" width="14" height="18" rx="2"></rect><path d="M9 3v18M12 8h4M12 12h4"></path></symbol>
                <symbol id="i-laptop" viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="11" rx="1.5"></rect><path d="M2 19h20"></path></symbol>
            </defs>
        </svg>

        @foreach ($objetos as $o)
            <svg class="objeto {{ $o['mobile'] ? '' : 'hidden sm:block' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"
                 style="--x: {{ $o['x'] }}%; --y: {{ $o['y'] }}%; --s: {{ $o['tam'] }}px; --r: {{ $o['rot'] }}deg; --dx: {{ $o['dx'] }}px; --dy: {{ $o['dy'] }}px; --dr: {{ $o['dr'] }}deg; --sc: {{ $o['sc'] }}; --sc2: {{ $o['sc2'] }}; --d: {{ $o['dur'] }}s; --a: -{{ $o['atraso'] }}s; --dc: {{ $o['dc'] }}s; --ac: -{{ $o['ac'] }}s; --c: {{ $o['cor'] }}; --o: {{ $o['op'] }};">
                <use href="#i-{{ $o['icone'] }}"></use>
            </svg>
        @endforeach
    </div>

    {{-- Cabeçalho (fixo no topo): logo, navegação e botões, tudo na barra roxa --}}
    <header x-data="{ rolou: false, aberto: false }"
            @scroll.window.passive="rolou = window.scrollY > 8"
            @keydown.escape.window="aberto = false"
            :class="rolou ? 'shadow-[0_10px_24px_-10px_rgba(0,0,0,0.55)]' : ''"
            class="sticky top-0 z-40 bg-[#29166F] transition-shadow duration-300">

        <div :class="rolou ? 'h-[56px]' : 'h-[66px]'"
             class="mx-auto flex max-w-[1240px] items-center gap-3 px-4 transition-[height] duration-300 sm:px-6">

            <a href="{{ route('home') }}" aria-label="Sumiu&Achou - página inicial"
               :class="rolou ? 'scale-90' : ''"
               class="shrink-0 origin-left transition duration-300 hover:-rotate-2 hover:opacity-90">
                <x-logo-sumiu-achou />
            </a>

            {{-- Navegação (telas médias e grandes) --}}
            <nav class="ml-4 hidden items-center gap-1 md:flex" aria-label="Principal">
            <a href="{{ $linkItens }}" @if ($ativoItens) aria-current="page" @endif
               class="group relative inline-flex h-10 items-center gap-2 rounded-full px-4 text-[12px] font-bold uppercase tracking-[0.06em] transition duration-300 {{ $ativoItens ? 'bg-white/[0.14] text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-4 w-4 text-[#00C2B3] transition duration-300 group-hover:scale-110 group-hover:-rotate-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect></svg>
                Todos os itens
            </a>
            <a href="{{ $linkForum }}" @if ($ativoForum) aria-current="page" @endif
               class="group relative inline-flex h-10 items-center gap-2 rounded-full px-4 text-[12px] font-bold uppercase tracking-[0.06em] transition duration-300 {{ $ativoForum ? 'bg-white/[0.14] text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-4 w-4 text-[#00C2B3] transition duration-300 group-hover:scale-110 group-hover:-rotate-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a8 8 0 0 1-8 8H6l-3 3V12a8 8 0 0 1 8-8h2a8 8 0 0 1 8 8Z"></path></svg>
                Fórum
            </a>
            </nav>

            <div class="flex-1"></div>

            {{-- Botões (telas médias e grandes) --}}
            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <a href="{{ route('dashboard') }}" class="group inline-flex h-10 items-center justify-center gap-2 rounded-full bg-[#00988D] px-5 text-[12px] font-bold uppercase tracking-[0.06em] text-white shadow-[0_3px_0_#006F67] transition duration-200 hover:-translate-y-0.5 hover:bg-[#00A99D] hover:shadow-[0_5px_0_#006F67] active:translate-y-0.5 active:shadow-[0_1px_0_#006F67]">
                        <svg class="h-4 w-4 transition duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                        MINHA ÁREA
                    </a>
                @else
                    <a href="{{ route('login') }}" class="group inline-flex h-10 items-center justify-center gap-2 rounded-full border-2 border-white/30 px-5 text-[12px] font-bold uppercase tracking-[0.06em] text-white transition duration-300 hover:border-white hover:bg-white/10 active:scale-95">
                        <svg class="h-4 w-4 transition duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                        ENTRAR
                    </a>
                    <a href="{{ route('register') }}" class="group inline-flex h-10 items-center justify-center gap-2 rounded-full bg-[#00988D] px-5 text-[12px] font-bold uppercase tracking-[0.06em] text-white shadow-[0_3px_0_#006F67] transition duration-200 hover:-translate-y-0.5 hover:bg-[#00A99D] hover:shadow-[0_5px_0_#006F67] active:translate-y-0.5 active:shadow-[0_1px_0_#006F67]">
                        <svg class="h-4 w-4 transition duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="4"></circle><path d="M2 21a7 7 0 0 1 14 0M19 8v6M16 11h6"></path></svg>
                        CRIAR CONTA
                    </a>
                @endauth
            </div>

            {{-- Botão do menu (celular) --}}
            <button type="button" @click="aberto = !aberto" :aria-expanded="aberto" aria-label="Abrir ou fechar o menu"
                    class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-white/25 text-white transition duration-200 hover:border-white active:scale-90 md:hidden">
                <svg x-show="!aberto" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"></path></svg>
                <svg x-show="aberto" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"></path></svg>
            </button>
        </div>

        {{-- Menu do celular --}}
        <div x-show="aberto" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-2 opacity-0"
             class="border-t border-white/10 md:hidden">
            <div class="mx-auto flex max-w-[1240px] flex-col gap-2 px-4 py-4 sm:px-6">
            <a href="{{ $linkItens }}" @click="aberto = false"
               class="flex h-12 items-center gap-3 rounded-2xl bg-white/[0.07] px-4 text-sm font-bold uppercase tracking-wide text-white transition duration-200 hover:bg-white/[0.14] active:scale-[0.98]">
                <svg class="h-5 w-5 text-[#00C2B3]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect></svg>
                Todos os itens
            </a>
            <a href="{{ $linkForum }}" @click="aberto = false"
               class="flex h-12 items-center gap-3 rounded-2xl bg-white/[0.07] px-4 text-sm font-bold uppercase tracking-wide text-white transition duration-200 hover:bg-white/[0.14] active:scale-[0.98]">
                <svg class="h-5 w-5 text-[#00C2B3]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a8 8 0 0 1-8 8H6l-3 3V12a8 8 0 0 1 8-8h2a8 8 0 0 1 8 8Z"></path></svg>
                Fórum
            </a>

                <div class="mt-2 grid gap-2 sm:grid-cols-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="group inline-flex h-10 items-center justify-center gap-2 rounded-full bg-[#00988D] px-5 text-[12px] font-bold uppercase tracking-[0.06em] text-white shadow-[0_3px_0_#006F67] transition duration-200 hover:-translate-y-0.5 hover:bg-[#00A99D] hover:shadow-[0_5px_0_#006F67] active:translate-y-0.5 active:shadow-[0_1px_0_#006F67] h-12 w-full sm:col-span-2">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                            MINHA ÁREA
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="group inline-flex h-10 items-center justify-center gap-2 rounded-full border-2 border-white/30 px-5 text-[12px] font-bold uppercase tracking-[0.06em] text-white transition duration-300 hover:border-white hover:bg-white/10 active:scale-95 h-12 w-full">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                            ENTRAR
                        </a>
                        <a href="{{ route('register') }}" class="group inline-flex h-10 items-center justify-center gap-2 rounded-full bg-[#00988D] px-5 text-[12px] font-bold uppercase tracking-[0.06em] text-white shadow-[0_3px_0_#006F67] transition duration-200 hover:-translate-y-0.5 hover:bg-[#00A99D] hover:shadow-[0_5px_0_#006F67] active:translate-y-0.5 active:shadow-[0_1px_0_#006F67] h-12 w-full">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="4"></circle><path d="M2 21a7 7 0 0 1 14 0M19 8v6M16 11h6"></path></svg>
                            CRIAR CONTA
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Fio verde no fim da barra --}}
        <div class="h-[3px] bg-[#00988D]"></div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- Rodapé --}}
    <footer class="bg-[#7B69B8] px-4 py-3 text-center text-xs text-[#29166F]">
        © {{ date('Y') }} Sumiu&amp;Achou - Site para fins educativos
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Revela os blocos conforme entram na tela
            var alvos = document.querySelectorAll('.revelar, .grupo');

            if (!('IntersectionObserver' in window)) {
                alvos.forEach(function (el) { el.classList.add('revelado'); });
                return;
            }

            var observador = new IntersectionObserver(function (entradas) {
                entradas.forEach(function (entrada) {
                    if (entrada.isIntersecting) {
                        entrada.target.classList.add('revelado');
                        observador.unobserve(entrada.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

            alvos.forEach(function (el) { observador.observe(el); });
        });
    </script>
</body>
</html>
