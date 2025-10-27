<?php

namespace App\Services;

use App\Models\Compte;
use App\Interfaces\CompteServiceInterface;
use App\Interfaces\CompteRepositoryInterface;
use App\DTOs\CreateCompteDto;
use App\DTOs\UpdateCompteDto;
use App\Enums\StatutEnum;
use App\Enums\ErrorEnum;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class CompteService implements CompteServiceInterface
{
    protected CompteRepositoryInterface $compteRepository;

    public function __construct(CompteRepositoryInterface $compteRepository)
    {
        $this->compteRepository = $compteRepository;
    }

    public function getAllComptes(int $perPage = 15): LengthAwarePaginator
    {
        return $this->compteRepository->findAll($perPage);
    }

    public function getCompteById(string $id): ?Compte
    {
        return $this->compteRepository->findById($id);
    }

    public function getCompteByNumero(string $numero): ?Compte
    {
        return $this->compteRepository->findByNumeroCompte($numero);
    }

    public function getComptesByTitulaire(int $titulaireId): LengthAwarePaginator
    {
        return $this->compteRepository->findByTitulaireId($titulaireId);
    }

    public function createCompte(CreateCompteDto $dto): Compte
    {
        $validator = Validator::make((array) $dto, CreateCompteDto::rules());
        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        // Business logic: Check if titulaire exists
            $client = \App\Models\Client::where('user_id', $dto->getTitulaireId())->first();
        if (!$client) {
            throw new \Exception(ErrorEnum::TITULAIRE_NOT_FOUND->value);
        }

        return $this->compteRepository->create($dto);
    }

    public function updateCompte(string $id, UpdateCompteDto $dto): Compte
    {
        $validator = Validator::make((array) $dto, UpdateCompteDto::rules());
        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return $this->compteRepository->update($id, $dto);
    }

    public function deleteCompte(string $id): bool
    {
        $compte = $this->compteRepository->findById($id);
        if (!$compte) {
            throw new \Exception(ErrorEnum::COMPTE_NOT_FOUND->value);
        }

        // Business logic: Check if account has transactions
        if ($compte->transactions()->count() > 0) {
            throw new \Exception(ErrorEnum::CANNOT_DELETE_ACCOUNT_WITH_TRANSACTIONS->value);
        }

        return $this->compteRepository->delete($id);
    }

    public function bloquerCompte(string $id, string $motif): Compte
    {
        $compte = $this->compteRepository->findById($id);
        if (!$compte) {
            throw new \Exception(ErrorEnum::COMPTE_NOT_FOUND->value);
        }

        if ($compte->statut === StatutEnum::BLOQUE->value) {
            throw new \Exception(ErrorEnum::ACCOUNT_ALREADY_BLOCKED->value);
        }

        return $this->compteRepository->bloquer($id, $motif);
    }

    public function debloquerCompte(string $id): Compte
    {
        $compte = $this->compteRepository->findById($id);
        if (!$compte) {
            throw new \Exception(ErrorEnum::COMPTE_NOT_FOUND->value);
        }

        if ($compte->statut !== StatutEnum::BLOQUE->value) {
            throw new \Exception(ErrorEnum::ACCOUNT_NOT_BLOCKED->value);
        }

        return $this->compteRepository->debloquer($id);
    }
}