<?php

namespace App\Enums;

enum PerfilUsuario: string
{
    case Comum = 'comum';
    case ServidorDdi = 'servidor_ddi';
    case Administrador = 'administrador';
}
