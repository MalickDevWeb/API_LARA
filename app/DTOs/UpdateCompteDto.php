<?php

namespace App\DTOs;

use App\Enums\CompteTypeEnum;
use App\Enums\DeviseEnum;
use App\Enums\StatutEnum;

class UpdateCompteDto
{
    public ?string $numero_compte = null;
    public ?int $titulaire_id = null;
    public ?CompteTypeEnum $type = null;
    public ?float $solde = null;
    public ?DeviseEnum $devise = null;
    public ?StatutEnum $statut = null;
    public ?string $motif_blocage = null;
    public ?array $metadata = null;

    public function __construct(array $data)
    {
        $this->numero_compte = $data['numero_compte'] ?? null;
        $this->titulaire_id = $data['titulaire_id'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->solde = $data['solde'] ?? null;
        $this->devise = $data['devise'] ?? null;
        $this->statut = $data['statut'] ?? null;
        $this->motif_blocage = $data['motif_blocage'] ?? null;
        $this->metadata = $data['metadata'] ?? null;
    }

    public static function rules(): array
    {
        return [
            'numero_compte' => 'sometimes|string|unique:comptes,numero_compte',
            'titulaire_id' => 'sometimes|integer|exists:client,user_id',
            'type' => 'sometimes|in:' . implode(',', array_column(CompteTypeEnum::cases(), 'value')),
            'solde' => 'sometimes|numeric|min:0',
            'devise' => 'sometimes|in:' . implode(',', array_column(DeviseEnum::cases(), 'value')),
            'statut' => 'sometimes|in:' . implode(',', array_column(StatutEnum::cases(), 'value')),
            'motif_blocage' => 'nullable|string',
            'metadata' => 'nullable|array',
        ];
    }
}