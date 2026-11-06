{{-- Logo "Sumiu&Achou" em HTML/SVG (sem imagem). O "O" de ACHOU é uma lupa. --}}
<span {{ $attributes->except('class')->merge(['class' => 'inline-flex select-none flex-col justify-center leading-none text-white']) }}
      role="img" aria-label="Sumiu&Achou">
    <span class="text-[15px] font-light tracking-wide">Sumiu<span class="font-semibold text-[#00C2B3]">&amp;</span></span>
    <span class="-mt-0.5 flex items-end text-[24px] font-semibold uppercase tracking-[0.08em]">ACH<svg class="mx-px mb-[3px] h-[22px] w-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><circle cx="10.5" cy="10.5" r="7"></circle><path d="m16 16 5 5" stroke="#00C2B3"></path></svg>U</span>
</span>
