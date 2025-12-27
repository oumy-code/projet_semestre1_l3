<?php

namespace App\Repository\Interface;

interface StatistiqueRepositoryInterface
{
    public function countCommandesEnCoursDuJour(): int;
    public function countCommandesValideesDuJour(): int;
    public function sumRecettesDuJour(): float;
    public function countCommandesAnnuleesDuJour(): int;
    public function findTopVentesDuJour(int $limit): array;
    public function findDernieresCommandes(int $limit): array;
}