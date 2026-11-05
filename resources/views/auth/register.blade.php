<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nome -->
        <div>
            <x-input-label for="name" value="Nome completo" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- E-mail -->
        <div class="mt-4">
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nome@aluno.edu.br" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Telefone (opcional) -->
        <div class="mt-4">
            <x-input-label for="telefone" value="Telefone (opcional)" />
            <x-text-input id="telefone" class="block mt-1 w-full" type="tel" name="telefone" :value="old('telefone')" autocomplete="tel" placeholder="(38) 99999-9999" />
            <x-input-error :messages="$errors->get('telefone')" class="mt-2" />
        </div>

        <!-- Senha -->
        <div class="mt-4">
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmar senha -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar senha" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Consentimento LGPD (RF32) -->
        <div class="mt-4">
            <label for="consentimento" class="inline-flex items-start">
                <input id="consentimento" type="checkbox" name="consentimento" value="1" required @checked(old('consentimento'))
                    class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                    Concordo com o tratamento dos meus dados pessoais (nome, e-mail e telefone) pelo sistema Sumiu&amp;Achou,
                    exclusivamente para a comunicação sobre itens achados e perdidos, conforme a Lei Geral de Proteção de Dados
                    (Lei nº 13.709/2018).
                </span>
            </label>
            <x-input-error :messages="$errors->get('consentimento')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                Já tem cadastro?
            </a>

            <x-primary-button class="ms-4">
                Cadastrar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
