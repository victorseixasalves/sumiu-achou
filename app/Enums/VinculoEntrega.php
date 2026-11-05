<?php

namespace App\Enums;

enum VinculoEntrega: string
{
    case Discente = 'discente';
    case Docente = 'docente';
    case Servidor = 'servidor';
    case Visitante = 'visitante';
}
