<?php

namespace App\Http\Middleware;

use App\Enums\PerfilUsuario;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controle de acesso por perfil (RF05, RNF08).
 *
 * Uso nas rotas: ->middleware('perfil:administrador,servidor_ddi')
 */
class VerificarPerfil
{
    public function handle(Request $request, Closure $next, string ...$perfis): Response
    {
        $usuario = $request->user();

        $permitidos = array_map(
            fn (string $perfil) => PerfilUsuario::from($perfil),
            $perfis
        );

        if (! $usuario || ! in_array($usuario->tipo, $permitidos, true)) {
            abort(403, 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
