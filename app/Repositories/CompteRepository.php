<?php

namespace App\Repositories;

use App\Models\Compte;
use App\Interfaces\CompteRepositoryInterface;
use App\DTOs\CreateCompteDto;
use App\DTOs\UpdateCompteDto;
use App\Enums\StatutEnum;
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
            'id' => $dto->numero_compte, // Assuming numero_compte is the ID
            'numero_compte' => $dto->numero_compte,
            'titulaire_id' => $dto->titulaire_id,
            'type' => $dto->type,
            'solde' => $dto->solde,
            'devise' => $dto->devise,
            'statut' => $dto->statut,
            'motif_blocage' => $dto->motif_blocage,
            'metadata' => $dto->metadata,
        ]);
    }

    public function update(string $id, UpdateCompteDto $dto): Compte
    {
        $compte = $this->findById($id);
        if (!$compte) {
            throw new \Exception('Compte not found');
        }

        $data = [];
        if ($dto->numero_compte !== null) $data['numero_compte'] = $dto->numero_compte;
        if ($dto->titulaire_id !== null) $data['titulaire_id'] = $dto->titulaire_id;
        if ($dto->type !== null) $data['type'] = $dto->type;
        if ($dto->solde !== null) $data['solde'] = $dto->solde;
        if ($dto->devise !== null) $data['devise'] = $dto->devise;
        if ($dto->statut !== null) $data['statut'] = $dto->statut;
        if ($dto->motif_blocage !== null) $data['motif_blocage'] = $dto->motif_blocage;
        if ($dto->metadata !== null) $data['metadata'] = $dto->metadata;

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
            throw new \Exception('Compte not found');
        }
        $compte->update(['statut' => StatutEnum::BLOQUE->value, 'motif_blocage' => $motif]);
        return $compte->fresh();
    }

    public function debloquer(string $id): Compte
    {
        $compte = $this->findById($id);
        if (!$compte) {
            throw new \Exception('Compte not found');
        }
        $compte->update(['statut' => StatutEnum::ACTIF->value, 'motif_blocage' => null]);
        return $compte->fresh();
    }
}