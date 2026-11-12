@props(['name', 'valor' => '1', 'marcado' => false, 'titulo' => 'Seus dados, sua escolha'])

{{-- Termo de consentimento: cartão com selo de proteção e chave liga/desliga. Fica verde quando aceito (estilos .termo-* no layout). O texto vai no slot. --}}
<label class="termo block cursor-pointer">
    <input type="checkbox" name="{{ $name }}" value="{{ $valor }}" @checked($marcado) {{ $attributes->merge(['class' => 'sr-only']) }}>

    <span class="termo-card block rounded-3xl bg-white p-4">
        <span class="flex items-center gap-3">
            <span class="termo-icone flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#29166F] text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3 4.5 6v5.5c0 4.6 3.1 8 7.5 9.5 4.4-1.5 7.5-4.9 7.5-9.5V6L12 3Z"></path>
                    <path class="termo-visto" d="m8.8 12.2 2.4 2.4 4.2-4.6"></path>
                </svg>
            </span>

            <span class="min-w-0 flex-1">
                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-[#00988D]">Termo de consentimento · LGPD</span>
                <span class="block text-sm font-extrabold leading-tight">{{ $titulo }}</span>
            </span>

            {{-- Chave liga/desliga --}}
            <span class="termo-chave relative h-7 w-12 shrink-0 rounded-full border-2 border-[#29166F]/35 bg-[#29166F]/10" aria-hidden="true">
                <span class="termo-bolinha absolute left-0.5 top-0.5 h-[18px] w-[18px] rounded-full bg-white shadow-[0_1px_0_#29166F]"></span>
            </span>
        </span>

        <span class="mt-3 block border-t-2 border-dashed border-[#29166F]/15 pt-3 text-xs font-medium leading-relaxed text-[#29166F]/80">{{ $slot }}</span>
    </span>
</label>
