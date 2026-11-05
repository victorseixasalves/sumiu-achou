<?php

namespace Database\Seeders;

use App\Models\Local;
use Illuminate\Database\Seeder;

class LocalSeeder extends Seeder
{
    public function run(): void
    {
        $locais = ['CCET', 'Biblioteca', 'Cantina', 'Bloco B'];

        foreach ($locais as $nome) {
            Local::firstOrCreate(['nome' => $nome]);
        }
    }
}
