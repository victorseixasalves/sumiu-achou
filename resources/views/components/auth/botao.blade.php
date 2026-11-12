{{-- Botão principal das telas de acesso: sombra sólida que "afunda" ao clicar e spinner enquanto envia (precisa de "enviando" no x-data do formulário). --}}
<button type="submit" :disabled="enviando"
        {{ $attributes->merge(['class' => 'group flex min-h-[3.25rem] w-full items-center justify-center gap-2 rounded-full bg-[#00988D] text-sm font-extrabold uppercase tracking-wider text-white shadow-[0_5px_0_#006F67] transition duration-200 hover:-translate-y-0.5 hover:bg-[#00A99D] hover:shadow-[0_7px_0_#006F67] active:translate-y-1 active:shadow-[0_1px_0_#006F67] disabled:cursor-wait disabled:opacity-80']) }}>
    <svg x-show="enviando" x-cloak class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-dasharray="14 44"></circle></svg>
    <span>{{ $slot }}</span>
    <svg x-show="!enviando" class="h-4 w-4 transition duration-200 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
</button>
