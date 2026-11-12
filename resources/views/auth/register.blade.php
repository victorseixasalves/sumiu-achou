<x-layouts.acesso titulo="Criar conta - Sumiu&Achou">
    <x-auth.moldura selo="Cadastro rápido" titulo="Crie sua conta e ache seu" destaque="item" texto="Com ela você procura o que perdeu e faz o pedido de devolução. Aberto a toda a comunidade acadêmica da Unimontes.">

        <div class="mb-7">
            <h2 class="text-3xl font-extrabold sm:text-4xl">Criar conta</h2>
            <p class="mt-1 text-sm text-[#29166F]/70">Leva menos de um minuto.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5"
              x-data="{
                  enviando: false,
                  senha: '',
                  conf: '',
                  get forca() {
                      const s = this.senha;
                      if (!s) return 0;
                      let n = 0;
                      if (s.length >= 8) n++;
                      if (/[a-z]/.test(s) && /[A-Z]/.test(s)) n++;
                      if (/[0-9]/.test(s)) n++;
                      if (/[^A-Za-z0-9]/.test(s)) n++;
                      return Math.max(1, Math.min(n, 3));
                  },
                  get rotulo() { return ['', 'Fraca', 'Média', 'Forte'][this.forca]; },
                  mascara(v) {
                      v = v.replace(/\D/g, '').slice(0, 11);
                      if (v.length > 10) return v.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
                      if (v.length > 6) return v.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
                      if (v.length > 2) return v.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
                      return v.length ? '(' + v : '';
                  }
              }"
              @submit="enviando = true" @pageshow.window="enviando = false">
            @csrf

            <x-auth.campo name="name" label="Nome completo" icone="usuario" placeholder="Como você se chama?"
                          required autofocus autocomplete="name" />

            <div class="space-y-5">
                <x-auth.campo name="email" label="E-mail" type="email" icone="email" placeholder="nome@aluno.edu.br"
                              required autocomplete="username" />

                <x-auth.campo name="telefone" label="Telefone" type="tel" icone="telefone" placeholder="Telefone (opcional)"
                              autocomplete="tel" inputmode="tel" maxlength="15"
                              x-on:input="$el.value = mascara($el.value)" />
            </div>

            <div class="space-y-5">
                <div>
                    <x-auth.campo name="password" label="Senha" type="password" icone="cadeado" placeholder="Mínimo de 8 caracteres"
                                  required autocomplete="new-password" x-model="senha" />

                    {{-- Medidor de força --}}
                    <div x-show="senha" x-cloak x-transition.opacity class="mt-2.5 flex items-center gap-2" aria-live="polite">
                        <div class="flex flex-1 gap-1.5">
                            <template x-for="i in 3" :key="i">
                                <span class="h-1.5 flex-1 rounded-full transition-all duration-300" :class="i <= forca ? 'bg-[#00988D]' : 'bg-[#29166F]/12'"></span>
                            </template>
                        </div>
                        <span class="w-10 text-right text-[11px] font-bold text-[#29166F]/70" x-text="rotulo"></span>
                    </div>
                </div>

                <div>
                    <x-auth.campo name="password_confirmation" label="Confirmar senha" type="password" icone="cadeado" placeholder="Repita a senha"
                                  required autocomplete="new-password" x-model="conf" />

                    <p x-show="conf" x-cloak x-transition.opacity class="mt-2 flex items-center gap-1.5 text-[11px] font-bold"
                       :class="conf === senha ? 'text-[#00988D]' : 'text-[#29166F]/55'">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path x-show="conf === senha" d="m5 12 5 5 9-10"></path>
                            <path x-show="conf !== senha" d="M6 6l12 12M18 6 6 18"></path>
                        </svg>
                        <span x-text="conf === senha ? 'As senhas conferem' : 'As senhas ainda não conferem'"></span>
                    </p>
                </div>
            </div>

            {{-- Consentimento LGPD (RF32) --}}
            <div>
                <x-auth.caixa name="consentimento" :marcado="(bool) old('consentimento')" required>
                    Concordo com o tratamento dos meus dados pessoais (nome, e-mail e telefone) pelo Sumiu&amp;Achou, exclusivamente
                    para a comunicação sobre itens achados e perdidos, conforme a Lei Geral de Proteção de Dados (Lei nº 13.709/2018).
                </x-auth.caixa>
                @error('consentimento')
                    <p class="mt-1.5 flex items-start gap-1.5 text-xs font-semibold text-red-600" role="alert">
                        <svg class="mt-px h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v6M12 16.5v.5"></path></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <x-auth.botao>Criar conta</x-auth.botao>
        </form>

        <div class="mt-7 border-t-2 border-dashed border-[#29166F]/15 pt-6 text-center">
            <p class="text-sm font-medium text-[#29166F]/70">
                Já tem cadastro?
                <a href="{{ route('login') }}" class="font-extrabold text-[#00988D] underline-offset-4 hover:underline">Entrar</a>
            </p>
            <a href="{{ route('home') }}" class="mt-3 inline-block text-xs font-bold text-[#29166F]/60 underline-offset-4 hover:text-[#29166F] hover:underline">← Voltar para a página inicial</a>
        </div>
    </x-auth.moldura>
</x-layouts.acesso>
