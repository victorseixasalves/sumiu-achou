<?php

namespace App\Http\Controllers\Auth;

use App\Enums\PerfilUsuario;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Exibe a tela de cadastro.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Cadastra um novo usuário (RF01), com consentimento LGPD (RF32).
     * O perfil nunca vem do formulário: todo cadastro público é "comum".
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'telefone' => ['nullable', 'string', 'regex:/^[0-9()+\-\s]{8,20}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'consentimento' => ['accepted'],
        ], [
            'telefone.regex' => 'Informe um telefone válido, com DDD.',
            'consentimento.accepted' => 'É necessário aceitar o tratamento de dados pessoais para se cadastrar.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'password' => Hash::make($request->password),
            'tipo' => PerfilUsuario::Comum,
            'ativo' => true,
            'consentimento_em' => now(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
