<?php

namespace App\Enum;

enum EtatCommande: string
{
    case EN_COURS = 'en_cours';
    case TERMINE = 'termine';
    case ANNULE = 'annule';

    public function getLabel(): string
    {
        return match($this) {
            self::EN_COURS => 'En cours',
            self::TERMINE => 'Terminé',
            self::ANNULE => 'Annulé',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::EN_COURS => 'orange',
            self::TERMINE => 'green',
            self::ANNULE => 'red',
        };
    }
}