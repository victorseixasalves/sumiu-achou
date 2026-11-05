<?php

namespace App\Enums;

enum StatusDevolucao: string
{
    case Solicitada = 'solicitada';
    case Aprovada = 'aprovada';
    case Rejeitada = 'rejeitada';
    case Concluida = 'concluida';
}
