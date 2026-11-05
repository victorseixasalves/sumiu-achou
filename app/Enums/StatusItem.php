<?php

namespace App\Enums;

enum StatusItem: string
{
    case Disponivel = 'disponivel';
    case Reservado = 'reservado';
    case Devolvido = 'devolvido';
    case EmTriagem = 'em_triagem';
    case Destinado = 'destinado';
}
