<?php

namespace App\Models;

use App\Enums\StatusItem;
use App\Enums\VinculoEntrega;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    // Exclusão lógica preservando o histórico para auditoria (RF13).
    use SoftDeletes;

    protected $table = 'itens';

    protected $fillable = [
        'nome',
        'categoria_id',
        'local_id',
        'data_achado',
        'data_entrega',
        'descricao',
        'condicao',
        'status',
        'foto',
        'entregue_por_nome',
        'entregue_por_vinculo',
        'usuario_cadastro_id',
    ];

    protected function casts(): array
    {
        return [
            'data_achado' => 'date',
            'data_entrega' => 'date',
            'status' => StatusItem::class,
            'entregue_por_vinculo' => VinculoEntrega::class,
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class, 'local_id');
    }

    /** "Cadastra": servidor que cadastrou o item. */
    public function usuarioCadastro(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_cadastro_id');
    }

    /** "Gera": solicitações de devolução do item. */
    public function devolucoes(): HasMany
    {
        return $this->hasMany(Devolucao::class, 'item_id');
    }

    public function scopeDisponiveis(Builder $query): Builder
    {
        return $query->where('status', StatusItem::Disponivel);
    }
}
