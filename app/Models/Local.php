<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Local extends Model
{
    protected $table = 'locais';

    protected $fillable = ['nome', 'endereco'];

    public function itens(): HasMany
    {
        return $this->hasMany(Item::class, 'local_id');
    }

    public function objetosPerdidos(): HasMany
    {
        return $this->hasMany(ObjetoPerdido::class, 'local_id');
    }
}
