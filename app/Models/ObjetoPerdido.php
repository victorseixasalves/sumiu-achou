<?php

namespace App\Models;

use App\Enums\StatusObjetoPerdido;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObjetoPerdido extends Model
{
    protected $table = 'objetos_perdidos';

    protected $fillable = [
        'usuario_id',
        'nome',
        'categoria_id',
        'local_id',
        'local_referencia',
        'descricao',
        'data_perda',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'data_perda' => 'date',
            'status' => StatusObjetoPerdido::class,
        ];
    }

    /** "Registra": usuário que registrou a perda. */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /** "Situa": local aproximado da perda (opcional). */
    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class, 'local_id');
    }
}
