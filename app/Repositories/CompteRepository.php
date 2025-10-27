<?php

namespace App\Repositories;

use App\Models\Compte;
use App\Interfaces\CompteRepositoryInterface;
use App\DTOs\CreateCompteDto;
use App\DTOs\UpdateCompteDto;
use App\Enums\StatutEnum;
use App\Enums\ErrorEnum;
use Illuminate\Pagination\LengthAwarePaginator;

class CompteRepository implements CompteRepositoryInterface
{
    public function findById(string $id): ?Compte
    {
        return Compte::with(['client.user', 'transactions'])->find($id);
    }

    public function findAll(int $perPage = 15): LengthAwarePaginator
    {
        return Compte::with(['client.user'])->paginate($perPage);
    }

    public function findByNumeroCompte(string $numero): ?Compte
    {
        return Compte::with(['client.user', 'transactions'])->where('numero_compte', $numero)->first();
    }

    public function findByTitulaireId(int $titulaireId): LengthAwarePaginator
    {
        return Compte::with(['client.user'])->where('titulaire_id', $titulaireId)->paginate(15);
    }

    public function create(CreateCompteDto $dto): Compte
    {
        return Compte::create([
                // id should be a uuid; numero_compte is a separate business identifier
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'numero_compte' => $dto->getNumeroCompte(),
                'titulaire_id' => $dto->getTitulaireId(),
                // store enum values to keep database columns as strings
                'type' => $dto->getType() instanceof \BackedEnum ? $dto->getType()->value : $dto->getType(),
                'devise' => $dto->getDevise() instanceof \BackedEnum ? $dto->getDevise()->value : $dto->getDevise(),
                'statut' => $dto->getStatut() instanceof \BackedEnum ? $dto->getStatut()->value : $dto->getStatut(),
                'motif_blocage' => $dto->getMotifBlocage(),
                'metadata' => $dto->getMetadata(),
        ]);
    }

    public function update(string $id, UpdateCompteDto $dto): Compte
    {
        $compte = $this->findById($id);
        if (!$compte) {
            throw new \Exception(ErrorEnum::COMPTE_NOT_FOUND->value);
        }

        $data = [];
            if ($dto->getNumeroCompte() !== null) $data['numero_compte'] = $dto->getNumeroCompte();
            if ($dto->getTitulaireId() !== null) $data['titulaire_id'] = $dto->getTitulaireId();
            if ($dto->getType() !== null) $data['type'] = $dto->getType();
            if ($dto->getDevise() !== null) $data['devise'] = $dto->getDevise();
            if ($dto->getStatut() !== null) $data['statut'] = $dto->getStatut();
            if ($dto->getMotifBlocage() !== null) $data['motif_blocage'] = $dto->getMotifBlocage();
            if ($dto->getMetadata() !== null) $data['metadata'] = $dto->getMetadata();

        $compte->update($data);
        return $compte->fresh();
    }

    public function delete(string $id): bool
    {
        $compte = $this->findById($id);
        if (!$compte) {
            return false;
        }
        return $compte->delete();
    }

    public function bloquer(string $id, string $motif): Compte
    {
        $compte = $this->findById($id);
        if (!$compte) {
            throw new \Exception(ErrorEnum::COMPTE_NOT_FOUND->value);
        }
        $compte->update(['statut' => StatutEnum::BLOQUE->value, 'motif_blocage' => $motif]);
        return $compte->fresh();
    }

    public function debloquer(string $id): Compte
    {
        $compte = $this->findById($id);
        if (!$compte) {
            throw new \Exception(ErrorEnum::COMPTE_NOT_FOUND->value);
        }
        $compte->update(['statut' => StatutEnum::ACTIF->value, 'motif_blocage' => null]);
        return $compte->fresh();
    }
}