<?php

namespace App\DTOs;

use App\Enums\CompteTypeEnum;
use App\Enums\DeviseEnum;
use App\Enums\StatutEnum;

class CreateCompteDto
{
    public string $numero_compte;
    public int $titulaire_id;
    public CompteTypeEnum $type;
    public float $solde;
    public DeviseEnum $devise;
    public StatutEnum $statut;
    public ?string $motif_blocage = null;
    public ?array $metadata = null;

    public function __construct(array $data)
    {
        $this->numero_compte = $data['numero_compte'];
        $this->titulaire_id = $data['titulaire_id'];
        $this->type = $data['type'];
        $this->solde = $data['solde'];
        $this->devise = $data['devise'];
        $this->statut = $data['statut'] ?? 'actif';
        $this->motif_blocage = $data['motif_blocage'] ?? null;
        $this->metadata = $data['metadata'] ?? null;
    }

    public static function rules(): array
    {
        return [
            'numero_compte' => 'required|string|unique:comptes,numero_compte',
            'titulaire_id' => 'required|integer|exists:client,user_id',
            'type' => 'required|in:' . implode(',', array_column(CompteTypeEnum::cases(), 'value')),
            'solde' => 'required|numeric|min:0',
            'devise' => 'required|in:' . implode(',', array_column(DeviseEnum::cases(), 'value')),
            'statut' => 'sometimes|in:' . implode(',', array_column(StatutEnum::cases(), 'value')),
            'motif_blocage' => 'nullable|string',
            'metadata' => 'nullable|array',
        ];
    }
}