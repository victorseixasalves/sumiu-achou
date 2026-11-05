<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = ['nome', 'descricao'];

    public function itens(): HasMany
    {
        return $this->hasMany(Item::class, 'categoria_id');
    }

    public function objetosPerdidos(): HasMany
    {
        return $this->hasMany(ObjetoPerdido::class, 'categoria_id');
    }
}
