<x-layouts.acesso titulo="Nova senha - Sumiu&Achou">
    <x-auth.moldura selo="Falta pouco" titulo="Quase" destaque="lá!" texto="Escolha uma senha nova para voltar a acessar a sua conta.">

        <div class="mb-7">
            <h2 class="text-3xl font-extrabold sm:text-4xl">Nova senha</h2>
            <p class="mt-1 text-sm text-[#29166F]/70">Confirme seu e-mail e escolha a nova senha.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5"
              x-data="{ enviando: false }" @submit="enviando = true" @pageshow.window="enviando = false">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <x-auth.campo name="email" label="E-mail" type="email" icone="email" :valor="$request->email" required autofocus autocomplete="username" />
            <x-auth.campo name="password" label="Nova senha" type="password" icone="cadeado" placeholder="Mínimo de 8 caracteres" required autocomplete="new-password" />
            <x-auth.campo name="password_confirmation" label="Confirmar nova senha" type="password" icone="cadeado" placeholder="Repita a senha" required autocomplete="new-password" />

            <x-auth.botao>Salvar nova senha</x-auth.botao>
        </form>
    </x-auth.moldura>
</x-layouts.acesso>
