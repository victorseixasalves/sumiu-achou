<?php

namespace App\Models;

use App\Enums\StatusDevolucao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class Devolucao extends Model
{
    protected $table = 'devolucoes';

    protected $fillable = [
        'item_id',
        'solicitante_id',
        'aprovador_id',
        'data_solicitacao',
        'comprovacao_propriedade',
        'data_decisao',
        'motivo_rejeicao',
        'data_efetivacao',
        'status',
        'numero_termo',
        'termo_arquivo',
    ];

    protected function casts(): array
    {
        return [
            'data_solicitacao' => 'datetime',
            'data_decisao' => 'datetime',
            'data_efetivacao' => 'datetime',
            'status' => StatusDevolucao::class,
        ];
    }

    protected static function booted(): void
    {
        // RN05: o termo de entrega não pode ser alterado após a emissão.
        static::updating(function (Devolucao $devolucao) {
            foreach (['numero_termo', 'termo_arquivo'] as $campo) {
                if ($devolucao->isDirty($campo) && $devolucao->getOriginal($campo) !== null) {
                    throw new LogicException(
                        "O campo {$campo} não pode ser alterado após a emissão do termo (RN05)."
                    );
                }
            }
        });
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id')->withTrashed();
    }

    /** "Solicita": usuário que solicitou a devolução. */
    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    /** "Aprova": servidor da DDI que decidiu a solicitação. */
    public function aprovador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprovador_id');
    }
}
