<x-layouts.acesso titulo="Entrar - Sumiu&Achou">
    <x-auth.moldura selo="Que bom te ver" titulo="Bem-vindo de" destaque="volta!" texto="Entre na sua conta para procurar seu item ou acompanhar o seu pedido de devolução.">

        <div class="mb-7">
            <h2 class="text-3xl font-extrabold sm:text-4xl">Entrar na conta</h2>
            <p class="mt-1 text-sm text-[#29166F]/70">Use o e-mail e a senha do seu cadastro.</p>
        </div>

        @if (session('status'))
            <div class="mb-5 flex items-start gap-2.5 rounded-2xl border-2 border-[#00988D] bg-[#E9F6F4] px-4 py-3 text-sm font-semibold text-[#006F67]" role="status">
                <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"></path></svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5"
              x-data="{ enviando: false }" @submit="enviando = true" @pageshow.window="enviando = false">
            @csrf

            <x-auth.campo name="email" label="E-mail" type="email" icone="email" placeholder="nome@aluno.edu.br"
                          required autofocus autocomplete="username" />

            <x-auth.campo name="password" label="Senha" type="password" icone="cadeado" placeholder="Sua senha"
                          required autocomplete="current-password" />

            <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2">
                <label class="group inline-flex cursor-pointer items-center gap-2.5">
                    <input type="checkbox" name="remember" id="remember_me" class="peer sr-only">
                    <span class="flex h-5 w-5 items-center justify-center rounded-md border-2 border-[#29166F]/40 bg-white text-transparent transition duration-300 group-hover:border-[#00988D] peer-checked:scale-110 peer-checked:border-[#29166F] peer-checked:bg-[#00988D] peer-checked:text-white peer-focus-visible:ring-4 peer-focus-visible:ring-[#00988D]/20">
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"></path></svg>
                    </span>
                    <span class="text-xs font-semibold text-[#29166F]/80">Manter conectado</span>
                </label>

                @if (\Illuminate\Support\Facades\Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#00988D] underline-offset-4 hover:underline">Esqueci a senha</a>
                @endif
            </div>

            <x-auth.botao>Entrar</x-auth.botao>
        </form>

        <p class="mt-7 text-center text-sm font-medium text-[#29166F]/70">
            Novo por aqui?
            <a href="{{ route('register') }}" class="font-extrabold text-[#00988D] underline-offset-4 hover:underline">Criar conta</a>
        </p>
        <a href="{{ route('home') }}" class="mt-3 block text-center text-xs font-bold text-[#29166F]/50 underline-offset-4 hover:text-[#29166F] hover:underline">← Voltar para a página inicial</a>
    </x-auth.moldura>
</x-layouts.acesso>
