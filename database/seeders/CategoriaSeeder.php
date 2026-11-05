<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Documentos',
            'Eletrônicos',
            'Vestuário',
            'Acessórios',
            'Material escolar',
            'Chaves',
            'Bolsas e mochilas',
            'Outros',
        ];

        foreach ($categorias as $nome) {
            Categoria::firstOrCreate(['nome' => $nome]);
        }
    }
}
