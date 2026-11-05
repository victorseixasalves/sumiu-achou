<?php

namespace App\Enums;

enum StatusObjetoPerdido: string
{
    case Aberto = 'aberto';
    case Resolvido = 'resolvido';
    case Cancelado = 'cancelado';
}
