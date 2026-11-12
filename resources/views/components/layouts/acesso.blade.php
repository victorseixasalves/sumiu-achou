@props(['titulo' => 'Sumiu&Achou'])
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#29166F">
    <title>{{ $titulo }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        @keyframes subir { from { opacity: 0; transform: translateY(24px) scale(.985); } to { opacity: 1; transform: none; } }
        @keyframes flutuar { 0%, 100% { transform: translateY(0) rotate(0); } 50% { transform: translateY(-9px) rotate(8deg); } }
        @keyframes derivar { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(10px, -12px); } }
        .cartao-acesso { animation: subir .7s cubic-bezier(.2, .7, .2, 1) both; }
        .flutua { animation: flutuar 6s ease-in-out infinite; transform-box: fill-box; transform-origin: center; }
        .deriva { animation: derivar 14s ease-in-out infinite; }

        @keyframes rota { to { stroke-dashoffset: -96; } }
        .rota { animation: rota 5s linear infinite; }

        /* Termo de consentimento (componente auth.caixa) */
        .termo-card { border: 2px solid #29166F; box-shadow: 4px 4px 0 #29166F; transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease; }
        .termo:hover .termo-card { transform: translateY(-1px); }
        .termo-icone, .termo-chave { transition: background-color .25s ease, border-color .25s ease, transform .35s cubic-bezier(.3, 1.6, .5, 1); }
        .termo-bolinha { transition: transform .3s cubic-bezier(.3, 1.6, .5, 1); }
        .termo-visto { stroke-dasharray: 16; stroke-dashoffset: 16; transition: stroke-dashoffset .35s ease .1s; }
        .termo input:focus-visible ~ .termo-card { outline: 3px solid rgba(0, 152, 141, .35); outline-offset: 3px; }
        .termo input:checked ~ .termo-card { border-color: #00988D; box-shadow: 4px 4px 0 #00988D; }
        .termo input:checked ~ .termo-card .termo-icone { background: #00988D; transform: rotate(-6deg) scale(1.06); }
        .termo input:checked ~ .termo-card .termo-chave { background: #00988D; border-color: #29166F; }
        .termo input:checked ~ .termo-card .termo-bolinha { transform: translateX(20px); }
        .termo input:checked ~ .termo-card .termo-visto { stroke-dashoffset: 0; }

        /* Fundo animado (objetos brancos) */
        @keyframes oscilar {
            0%, 100% { transform: translate3d(0, 0, 0) rotate(var(--r)) scale(1); }
            25%      { transform: translate3d(var(--dx), calc(var(--dy) * .4), 0) rotate(calc(var(--r) + var(--dr) * .6)) scale(var(--sc)); }
            50%      { transform: translate3d(calc(var(--dx) * .4), var(--dy), 0) rotate(calc(var(--r) + var(--dr))) scale(1); }
            75%      { transform: translate3d(calc(var(--dx) * -.8), calc(var(--dy) * .5), 0) rotate(calc(var(--r) - var(--dr) * .6)) scale(var(--sc2)); }
        }
        @keyframes pulsar { 0%, 100% { opacity: var(--o); } 50% { opacity: calc(var(--o) * .45); } }
        .fundo-acesso { position: fixed; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; }
        .fundo-acesso .objeto { position: absolute; left: var(--x); top: var(--y); width: var(--s); height: var(--s); color: #fff; opacity: var(--o); will-change: transform;
                                animation: oscilar var(--d) ease-in-out var(--a) infinite, pulsar var(--dc) ease-in-out var(--ac) infinite; }
        @media (prefers-reduced-motion: reduce) { .cartao-acesso, .flutua, .deriva, .rota, .fundo-acesso .objeto { animation: none !important; } }
    </style>
</head>
<body class="min-h-screen bg-[#29166F] text-[#29166F] antialiased" style="font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;">
    <x-fundo-acesso />

    <main class="flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 sm:py-12">
        {{ $slot }}
    </main>
</body>
</html>
