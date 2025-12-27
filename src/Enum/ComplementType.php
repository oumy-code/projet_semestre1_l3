<?php

namespace App\Enum;

enum ComplementType: string
{
    case FRITES = 'FRITES';
    case BOISSON = 'BOISSON';

    public function getLabel(): string
    {
        return match($this) {
            self::FRITES => 'Frites',
            self::BOISSON => 'Boisson',
        };
    }
}