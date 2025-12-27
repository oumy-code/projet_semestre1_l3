<?php

namespace App\Enum;

enum TypeRecuperation: string
{
    case SUR_PLACE = 'sur_place';
    case A_RECUPERER = 'a_recuperer';
    case LIVRAISON = 'livraison';

    public function getLabel(): string
    {
        return match($this) {
            self::SUR_PLACE => 'Sur place',
            self::A_RECUPERER => 'À récupérer',
            self::LIVRAISON => 'Livraison',
        };
    }
}