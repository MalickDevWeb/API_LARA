<?php

namespace App\Interfaces;

use App\Models\Compte;
use App\DTOs\CreateCompteDto;
use App\DTOs\UpdateCompteDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompteServiceInterface
{
    public function getAllComptes(int $perPage = 15): LengthAwarePaginator;

    public function getCompteById(string $id): ?Compte;

    public function getCompteByNumero(string $numero): ?Compte;

    public function getComptesByTitulaire(int $titulaireId): LengthAwarePaginator;

    public function createCompte(CreateCompteDto $dto): Compte;

    public function updateCompte(string $id, UpdateCompteDto $dto): Compte;

    public function deleteCompte(string $id): bool;

    public function bloquerCompte(string $id, string $motif): Compte;

    public function debloquerCompte(string $id): Compte;
}