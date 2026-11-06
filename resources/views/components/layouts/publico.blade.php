@props(['titulo' => 'Sumiu&Achou'])

@php
    $rotas = \Illuminate\Support\Facades\Route::class;
    $linkItens = $rotas::has('itens.index') ? route('itens.index') : '#';
    $linkForum = $rotas::has('forum.index') ? route('forum.index') : '#';
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titulo }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="flex min-h-screen flex-col bg-[#F5FAFF] text-[#29166F] antialiased"
      style="font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;">

    {{-- Cabeçalho --}}
    <header class="bg-[#29166F]">
        <div class="mx-auto flex h-[52px] max-w-[1100px] items-center justify-between px-4">
            <a href="{{ route('home') }}" aria-label="Sumiu&Achou - página inicial">
                <x-logo-sumiu-achou class="h-9 w-auto" />
            </a>

            @auth
                <a href="{{ route('dashboard') }}"
                   class="rounded-full border border-[#00988D] px-6 py-1.5 text-xs font-bold tracking-wide text-[#00988D] transition hover:bg-[#00988D] hover:text-white">
                    MINHA ÁREA
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="rounded-full border border-[#00988D] px-8 py-1.5 text-xs font-bold tracking-wide text-[#00988D] transition hover:bg-[#00988D] hover:text-white">
                    ENTRAR
                </a>
            @endauth
        </div>
    </header>

    {{-- Barra de navegação --}}
    <nav class="mx-auto flex w-full max-w-[1060px] justify-end gap-2 rounded-b-2xl bg-[#00988D] px-4 py-2" aria-label="Principal">
        <a href="{{ $linkItens }}"
           class="rounded-full border border-[#29166F] px-5 py-1 text-[11px] font-bold uppercase text-[#29166F] transition hover:bg-[#29166F] hover:text-white">
            Todos os itens
        </a>
        <a href="{{ $linkForum }}"
           class="rounded-full border border-[#29166F] px-5 py-1 text-[11px] font-bold uppercase text-[#29166F] transition hover:bg-[#29166F] hover:text-white">
            Fórum
        </a>
    </nav>

    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- Rodapé --}}
    <footer class="bg-[#7B69B8] py-3 text-center text-xs text-[#29166F]">
        © {{ date('Y') }} Sumiu&amp;Achou - Site para fins educativos
    </footer>
</body>
</html>
