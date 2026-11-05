<?php

namespace App\Models\Concerns;

use App\Enums\PerfilUsuario;
use App\Models\Devolucao;
use App\Models\Item;
use App\Models\LogAuditoria;
use App\Models\Noticia;
use App\Models\ObjetoPerdido;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Campos, casts, relacionamentos e perfis de acesso da entidade USER (MER v3).
 * Usada pelo Model App\Models\User.
 */
trait TemPerfilDeAcesso
{
    /**
     * Executado automaticamente pelo Eloquent ao instanciar o Model.
     */
    public function initializeTemPerfilDeAcesso(): void
    {
        $this->mergeFillable(['telefone', 'tipo', 'ativo', 'consentimento_em']);

        $this->mergeCasts([
            'tipo' => PerfilUsuario::class,
            'ativo' => 'boolean',
            'consentimento_em' => 'datetime',
            'anonimizado_em' => 'datetime',
        ]);
    }

    // --- Perfis de acesso (RF04 e RF05) ---

    public function ehAdministrador(): bool
    {
        return $this->tipo === PerfilUsuario::Administrador;
    }

    public function ehServidorDdi(): bool
    {
        return $this->tipo === PerfilUsuario::ServidorDdi;
    }

    public function ehUsuarioComum(): bool
    {
        return $this->tipo === PerfilUsuario::Comum;
    }

    // --- Relacionamentos ---

    /** "Cadastra": itens cadastrados pelo usuário. */
    public function itensCadastrados(): HasMany
    {
        return $this->hasMany(Item::class, 'usuario_cadastro_id');
    }

    /** "Administra": avisos publicados pelo usuário. */
    public function noticias(): HasMany
    {
        return $this->hasMany(Noticia::class, 'usuario_id');
    }

    /** "Solicita": devoluções solicitadas pelo usuário. */
    public function devolucoesSolicitadas(): HasMany
    {
        return $this->hasMany(Devolucao::class, 'solicitante_id');
    }

    /** "Aprova": devoluções decididas pelo usuário (servidor da DDI). */
    public function devolucoesDecididas(): HasMany
    {
        return $this->hasMany(Devolucao::class, 'aprovador_id');
    }

    /** "Registra": perdas registradas pelo usuário. */
    public function objetosPerdidos(): HasMany
    {
        return $this->hasMany(ObjetoPerdido::class, 'usuario_id');
    }

    /** "Realiza": ações auditadas realizadas pelo usuário. */
    public function logsAuditoria(): HasMany
    {
        return $this->hasMany(LogAuditoria::class, 'usuario_id');
    }
}
