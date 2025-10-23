<?php

namespace App\Interfaces;

use App\Models\Compte;
use App\DTOs\CreateCompteDto;
use App\DTOs\UpdateCompteDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompteRepositoryInterface
{
    public function findById(string $id): ?Compte;

    public function findAll(int $perPage = 15): LengthAwarePaginator;

    public function findByNumeroCompte(string $numero): ?Compte;

    public function findByTitulaireId(int $titulaireId): LengthAwarePaginator;

    public function create(CreateCompteDto $dto): Compte;

    public function update(string $id, UpdateCompteDto $dto): Compte;

    public function delete(string $id): bool;

    public function bloquer(string $id, string $motif): Compte;

    public function debloquer(string $id): Compte;
}