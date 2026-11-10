<?php

namespace Database\Seeders;

use App\Enums\StatusItem;
use App\Models\Categoria;
use App\Models\Item;
use App\Models\Local;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Itens de exemplo (APENAS para desenvolvimento) para testar a lista, a busca,
 * os filtros e a paginação.
 */
class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $servidor = User::where('email', 'ddi@teste.local')->firstOrFail();

        // [nome, categoria, local, data, descrição, status (opcional)]
        $itens = [
            ['Capacete', 'Acessórios', 'CCET', '2026-05-05', 'Capacete preto de motocicleta.'],
            ['Carteira preta', 'Documentos', 'CCET', '2026-05-05', 'Carteira de couro preta com zíper. Sem dinheiro.'],
            ['Mochila', 'Bolsas e mochilas', 'Biblioteca', '2026-05-04', 'Mochila azul com estojo e cadernos.'],
            ['Garrafa', 'Acessórios', 'CCET', '2026-05-03', 'Garrafa térmica azul.'],
            ['Caderno', 'Material escolar', 'Bloco B', '2026-05-02', 'Caderno universitário com capa vermelha.'],
            ['Chaves', 'Chaves', 'Cantina', '2026-04-30', 'Chave com chaveiro azul.'],
            ['Fone de ouvido', 'Eletrônicos', 'Biblioteca', '2026-04-29', 'Fone sem fio branco, na case.', StatusItem::Reservado],
            ['Guarda-chuva', 'Acessórios', 'Bloco B', '2026-04-28', 'Guarda-chuva preto automático.'],
            ['Casaco', 'Vestuário', 'CCET', '2026-04-27', 'Casaco de moletom cinza, tamanho M.'],
            ['Óculos de grau', 'Acessórios', 'Cantina', '2026-04-24', 'Armação preta, com estojo.'],
            ['Calculadora científica', 'Eletrônicos', 'Bloco B', '2026-04-22', 'Calculadora com capa cinza.'],
            ['Crachá', 'Documentos', 'Biblioteca', '2026-04-20', 'Crachá de estudante com cordão verde.'],
            ['Livro de Cálculo', 'Material escolar', 'Biblioteca', '2026-04-18', 'Livro de capa azul com anotações.', StatusItem::Reservado],
            ['Estojo', 'Material escolar', 'Cantina', '2026-04-15', 'Estojo vermelho com zíper dourado.'],
        ];

        foreach ($itens as $linha) {
            [$nome, $categoria, $local, $data, $descricao] = $linha;
            $status = $linha[5] ?? StatusItem::Disponivel;

            Item::updateOrCreate(
                ['nome' => $nome, 'data_achado' => $data],
                [
                    'categoria_id' => Categoria::where('nome', $categoria)->firstOrFail()->id,
                    'local_id' => Local::where('nome', $local)->firstOrFail()->id,
                    'data_entrega' => $data,
                    'descricao' => $descricao,
                    'status' => $status,
                    'usuario_cadastro_id' => $servidor->id,
                ]
            );
        }
    }
}
