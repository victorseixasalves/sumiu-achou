<x-layouts.acesso titulo="Recuperar senha - Sumiu&Achou">
    <x-auth.moldura selo="Sem estresse" titulo="Esqueceu a" destaque="senha?" texto="Informe seu e-mail e enviaremos um link para você criar uma nova.">

        <div class="mb-7">
            <h2 class="text-3xl font-extrabold sm:text-4xl">Recuperar senha</h2>
            <p class="mt-1 text-sm text-[#29166F]/70">Sem problema, acontece com todo mundo.</p>
        </div>

        @if (session('status'))
            <div class="mb-5 flex items-start gap-2.5 rounded-2xl border-2 border-[#00988D] bg-[#E9F6F4] px-4 py-3 text-sm font-semibold text-[#006F67]" role="status">
                <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"></path></svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5"
              x-data="{ enviando: false }" @submit="enviando = true" @pageshow.window="enviando = false">
            @csrf

            <x-auth.campo name="email" label="E-mail" type="email" icone="email" placeholder="nome@aluno.edu.br" required autofocus />

            <x-auth.botao>Enviar link</x-auth.botao>
        </form>

        <div class="mt-7 border-t-2 border-dashed border-[#29166F]/15 pt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm font-extrabold text-[#00988D] underline-offset-4 hover:underline">← Voltar para entrar</a>
        </div>
    </x-auth.moldura>
</x-layouts.acesso>
