<?php

namespace Database\Seeders;

use App\Enums\StatusItem;
use App\Models\Categoria;
use App\Models\Item;
use App\Models\Local;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Itens de exemplo para testar listagem, busca e filtros.
 */
class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $servidor = User::where('email', 'ddi@teste.local')->firstOrFail();

        $itens = [
            ['Carteira preta', 'Documentos', 'CCET', '2026-05-05', 'Carteira de couro preta com zíper. Sem dinheiro.'],
            ['Mochila', 'Bolsas e mochilas', 'Biblioteca', '2026-05-04', 'Mochila azul com estojo e cadernos.'],
            ['Garrafa', 'Acessórios', 'CCET', '2026-05-03', 'Garrafa térmica azul.'],
            ['Caderno', 'Material escolar', 'Bloco B', '2026-05-02', 'Caderno universitário com capa vermelha.'],
            ['Chaves', 'Chaves', 'Cantina', '2026-04-30', 'Chave com chaveiro azul.'],
            ['Capacete', 'Acessórios', 'CCET', '2026-05-05', 'Capacete preto de motocicleta.'],
        ];

        foreach ($itens as [$nome, $categoria, $local, $data, $descricao]) {
            Item::updateOrCreate(
                ['nome' => $nome, 'data_achado' => $data],
                [
                    'categoria_id' => Categoria::where('nome', $categoria)->firstOrFail()->id,
                    'local_id' => Local::where('nome', $local)->firstOrFail()->id,
                    'data_entrega' => $data,
                    'descricao' => $descricao,
                    'status' => StatusItem::Disponivel,
                    'usuario_cadastro_id' => $servidor->id,
                ]
            );
        }
    }
}
