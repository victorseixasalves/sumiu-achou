<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class LogAuditoria extends Model
{
    // A tabela só possui created_at (preenchido na criação).
    const UPDATED_AT = null;

    protected $table = 'logs_auditoria';

    protected $fillable = [
        'usuario_id',
        'acao',
        'entidade',
        'entidade_id',
        'dados_anteriores',
        'dados_novos',
        'ip',
    ];

    protected function casts(): array
    {
        return [
            'dados_anteriores' => 'array',
            'dados_novos' => 'array',
            'created_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // RNF10: registros de auditoria são imutáveis (somente inserção e consulta).
        static::updating(function () {
            throw new LogicException('Registros de auditoria são imutáveis (RNF10).');
        });

        static::deleting(function () {
            throw new LogicException('Registros de auditoria não podem ser excluídos (RNF10).');
        });
    }

    /** "Realiza": usuário que realizou a ação (nulo em ações automáticas). */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
