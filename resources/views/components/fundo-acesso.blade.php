{{-- Fundo animado das telas de acesso: objetos de achados e perdidos em BRANCO (para aparecer sobre o roxo), se movendo e pulsando de leve. --}}
@php
    $icones = ['chave', 'carteira', 'mochila', 'celular', 'oculos', 'guardachuva', 'cracha', 'fone', 'livro', 'garrafa', 'caderno', 'laptop', 'pin', 'lupa'];
    $colunas = 11;
    $linhas = 6;
    $objetos = [];
    $n = 0;
    mt_srand(23); // posições fixas
    for ($l = 0; $l < $linhas; $l++) {
        for ($c = 0; $c < $colunas; $c++) {
            $objetos[] = [
                'icone' => $icones[($n * 5) % count($icones)],
                'x' => round((($c + 0.1 + mt_rand(0, 80) / 100) / $colunas) * 100, 1),
                'y' => round((($l + 0.1 + mt_rand(0, 80) / 100) / $linhas) * 100, 1),
                'tam' => mt_rand(30, 54),
                'rot' => mt_rand(-28, 28),
                'dx' => mt_rand(-34, 34),
                'dy' => mt_rand(-38, 38),
                'dr' => (mt_rand(0, 1) ? 1 : -1) * mt_rand(10, 22),
                'sc' => mt_rand(108, 116) / 100,
                'sc2' => mt_rand(86, 94) / 100,
                'dur' => mt_rand(8, 14),
                'atraso' => mt_rand(0, 14),
                'dc' => mt_rand(5, 11),
                'ac' => mt_rand(0, 11),
                'op' => mt_rand(14, 30) / 100,
                'mobile' => $n % 2 === 0,
            ];
            $n++;
        }
    }
@endphp

<div class="fundo-acesso" aria-hidden="true">
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
             style="--x: {{ $o['x'] }}%; --y: {{ $o['y'] }}%; --s: {{ $o['tam'] }}px; --r: {{ $o['rot'] }}deg; --dx: {{ $o['dx'] }}px; --dy: {{ $o['dy'] }}px; --dr: {{ $o['dr'] }}deg; --sc: {{ $o['sc'] }}; --sc2: {{ $o['sc2'] }}; --d: {{ $o['dur'] }}s; --a: -{{ $o['atraso'] }}s; --dc: {{ $o['dc'] }}s; --ac: -{{ $o['ac'] }}s; --o: {{ $o['op'] }};">
            <use href="#i-{{ $o['icone'] }}"></use>
        </svg>
    @endforeach
</div>
