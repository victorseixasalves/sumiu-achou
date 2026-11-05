<?php

namespace App\Models;

use App\Enums\StatusNoticia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Noticia extends Model
{
    protected $table = 'noticias';

    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'data_publicacao',
        'usuario_id',
    ];

    protected function casts(): array
    {
        return [
            'data_publicacao' => 'datetime',
            'status' => StatusNoticia::class,
        ];
    }

    /** "Administra": administrador que publicou o aviso. */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function scopeAtivas(Builder $query): Builder
    {
        return $query->where('status', StatusNoticia::Ativo);
    }
}
