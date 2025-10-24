<?php

namespace App\DTOs;

use App\Enums\CompteTypeEnum;
use App\Enums\DeviseEnum;
use App\Enums\StatutEnum;

/**
 * @OA\Schema(
 *     schema="UpdateCompteDto",
 *     type="object",
 *     title="UpdateCompteDto",
 *     description="DTO pour mettre à jour un compte",
 *     @OA\Property(property="numero_compte", type="string", description="Numéro du compte"),
 *     @OA\Property(property="titulaire_id", type="integer", description="ID du titulaire"),
 *     @OA\Property(property="type", type="string", enum={"courant", "epargne"}, description="Type de compte"),
 *     @OA\Property(property="devise", type="string", enum={"XOF", "EUR", "USD"}, description="Devise du compte"),
 *     @OA\Property(property="statut", type="string", enum={"actif", "bloque", "ferme"}, description="Statut du compte"),
 *     @OA\Property(property="motif_blocage", type="string", description="Motif de blocage"),
 *     @OA\Property(property="metadata", type="object", description="Métadonnées")
 * )
 */
class UpdateCompteDto
{
    public ?string $numero_compte = null;
    public ?int $titulaire_id = null;
    public ?CompteTypeEnum $type = null;
    public ?DeviseEnum $devise = null;
    public ?StatutEnum $statut = null;
    public ?string $motif_blocage = null;
    public ?array $metadata = null;

    public function __construct(array $data)
    {
        $this->numero_compte = $data['numero_compte'] ?? null;
        $this->titulaire_id = $data['titulaire_id'] ?? null;
        $this->type = $data['type'] ?? null;
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
            'devise' => 'sometimes|in:' . implode(',', array_column(DeviseEnum::cases(), 'value')),
            'statut' => 'sometimes|in:' . implode(',', array_column(StatutEnum::cases(), 'value')),
            'motif_blocage' => 'nullable|string',
            'metadata' => 'nullable|array',
        ];
    }
}