@props(['name', 'label', 'type' => 'text', 'icone' => 'usuario', 'ajuda' => null, 'valor' => null, 'opcional' => false])

@php
    $icones = [
        'usuario'  => '<circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path>',
        'email'    => '<rect x="3" y="5" width="18" height="14" rx="2.5"></rect><path d="m3 7 9 6 9-6"></path>',
        'telefone' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"></path>',
        'cadeado'  => '<rect x="4" y="11" width="16" height="10" rx="2.5"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path>',
    ];
    $erro = $errors->first($name);
    $senha = $type === 'password';
@endphp

<div @if ($senha) x-data="{ ver: false }" @endif>
    <label for="{{ $name }}" class="sr-only">{{ $label }}</label>

    <div class="group relative">
        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[#29166F]/45 transition duration-200 group-focus-within:scale-110 group-focus-within:text-[#7B69B8] {{ $erro ? '!text-red-500' : '' }}">
            <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icones[$icone] ?? $icones['usuario'] !!}</svg>
        </span>

        <input id="{{ $name }}" name="{{ $name }}"
               @if ($senha) :type="ver ? 'text' : 'password'" @else type="{{ $type }}" @endif
               @unless ($senha) value="{{ old($name, $valor) }}" @endunless
               {{ $attributes->merge(['class' => 'h-12 w-full rounded-full border-2 bg-white pl-12 text-sm font-semibold text-[#29166F] placeholder-[#29166F]/35 transition duration-200 hover:border-[#7B69B8] focus:border-[#7B69B8] focus:ring-4 focus:ring-[#7B69B8]/20 '.($senha ? 'pr-12 ' : 'pr-4 ').($erro ? 'border-red-400' : 'border-[#29166F]/20')]) }}>

        @if ($senha)
            <button type="button" @click="ver = !ver" :aria-label="ver ? 'Esconder a senha' : 'Mostrar a senha'" :aria-pressed="ver"
                    class="absolute right-2.5 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full text-[#29166F]/50 transition duration-200 hover:bg-[#EDE8FB] hover:text-[#29166F] active:scale-90">
                <svg x-show="!ver" class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                <svg x-show="ver" x-cloak class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18M10.6 5.1A10 10 0 0 1 12 5c6.4 0 10 7 10 7a17 17 0 0 1-3.2 4M6.5 6.6C3.7 8.4 2 12 2 12s3.6 7 10 7a9.7 9.7 0 0 0 4-.9M9.9 9.9a3 3 0 0 0 4.2 4.2"></path></svg>
            </button>
        @endif
    </div>

    @if ($erro)
        <p class="mt-1.5 flex items-start gap-1.5 pl-4 text-xs font-semibold text-red-600" role="alert">
            <svg class="mt-px h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v6M12 16.5v.5"></path></svg>
            {{ $erro }}
        </p>
    @elseif ($ajuda)
        <p class="mt-1.5 pl-4 text-[11px] font-medium text-[#29166F]/55">{{ $ajuda }}</p>
    @endif
</div>
