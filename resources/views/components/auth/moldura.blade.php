@props(['selo' => '', 'titulo' => '', 'destaque' => '', 'texto' => ''])

{{-- Cartão das telas de acesso: painel roxo com a logo grande à esquerda e o formulário à direita. --}}
<div class="cartao-acesso grid w-full max-w-[1080px] overflow-hidden rounded-[2rem] bg-white shadow-[10px_10px_0_#1B0E52] md:grid-cols-2">

    {{-- ============ PAINEL ROXO ============ --}}
    <aside class="relative flex min-h-[240px] items-center justify-center overflow-hidden bg-[#29166F] px-8 py-10 text-center text-white md:min-h-[600px] lg:px-12">
        <x-auth.decoracao />

        <div class="relative flex w-full flex-col items-center">
            <a href="{{ route('home') }}" aria-label="Sumiu&Achou - página inicial" class="block w-[78%] max-w-[390px] transition duration-300 hover:scale-[1.03] md:w-[92%]">
                <img src="{{ asset('images/logo-sumiu-achou.png') }}" alt="Sumiu&Achou" width="640" height="229" class="h-auto w-full">
            </a>

            @if ($selo)
                <p class="mt-7 flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.2em] text-[#B6A9F0]">
                    <span class="h-[3px] w-6 rounded-full bg-[#00C2B3]"></span>
                    {{ $selo }}
                    <span class="h-[3px] w-6 rounded-full bg-[#00C2B3]"></span>
                </p>
            @endif

            <h1 class="mt-3 max-w-[15ch] text-[1.9rem] font-extrabold leading-[1.12] tracking-tight [text-wrap:balance] sm:text-4xl lg:text-[2.6rem]">
                {{ $titulo }}
                @if ($destaque)
                    <span class="relative inline-block text-[#00C2B3]">
                        {{ $destaque }}
                        <svg class="absolute -bottom-1.5 left-0 h-2.5 w-full" viewBox="0 0 104 10" preserveAspectRatio="none" fill="none" aria-hidden="true">
                            <path d="M2 5Q12 0 22 5T42 5T62 5T82 5T102 5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"></path>
                        </svg>
                    </span>
                @endif
            </h1>

            <p class="mt-4 max-w-[34ch] text-[15px] font-medium leading-relaxed text-[#DDD5FB] sm:text-base">{{ $texto }}</p>
        </div>
    </aside>

    {{-- ============ FORMULÁRIO ============ --}}
    <div class="flex flex-col justify-center p-7 sm:p-10 lg:px-14 lg:py-12">
        {{ $slot }}
    </div>
</div>
