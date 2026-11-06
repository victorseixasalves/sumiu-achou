{{--
    Exibe uma foto do site se o arquivo existir; caso contrário, mostra o conteúdo do slot (placeholder).
    Basta colocar o arquivo em public/ com o nome base indicado (jpg, jpeg, png ou webp).
    Ex.: nome="images/home/hero" -> public/images/home/hero.jpg
--}}
@props(['nome', 'alt' => ''])

@php
    $arquivo = null;
    foreach (['jpg', 'jpeg', 'png', 'webp'] as $extensao) {
        if (file_exists(public_path("{$nome}.{$extensao}"))) {
            $arquivo = "{$nome}.{$extensao}";
            break;
        }
    }
@endphp

@if ($arquivo)
    <img src="{{ asset($arquivo) }}?v={{ filemtime(public_path($arquivo)) }}"
         alt="{{ $alt }}"
         {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}>
@else
    {{ $slot }}
@endif
