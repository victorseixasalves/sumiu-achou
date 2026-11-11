@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginação" class="paginacao-itens flex flex-wrap items-center justify-center gap-2">

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-[#29166F]/20 text-[#29166F]/30">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5-7 7 7 7"></path></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior"
               class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-[#29166F] bg-white text-[#29166F] shadow-[0_3px_0_#29166F] transition duration-200 hover:bg-[#29166F] hover:text-white active:translate-y-0.5 active:shadow-none">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5-7 7 7 7"></path></svg>
            </a>
        @endif

        {{-- Números --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1 text-[#29166F]/50">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $pagina => $url)
                    @if ($pagina == $paginator->currentPage())
                        <span aria-current="page"
                              class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-full bg-[#29166F] px-3 text-sm font-extrabold text-white shadow-[0_3px_0_#006F67]">{{ $pagina }}</span>
                    @else
                        <a href="{{ $url }}" aria-label="Ir para a página {{ $pagina }}"
                           class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-full border-2 border-[#29166F]/25 bg-white px-3 text-sm font-bold text-[#29166F] transition duration-200 hover:border-[#29166F] hover:bg-[#29166F] hover:text-white">{{ $pagina }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Próxima --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Próxima página"
               class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-[#29166F] bg-white text-[#29166F] shadow-[0_3px_0_#29166F] transition duration-200 hover:bg-[#29166F] hover:text-white active:translate-y-0.5 active:shadow-none">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"></path></svg>
            </a>
        @else
            <span aria-disabled="true" class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-[#29166F]/20 text-[#29166F]/30">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"></path></svg>
            </span>
        @endif
    </nav>
@endif
