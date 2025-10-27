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

        // Convert type
        if (isset($data['type'])) {
            if ($data['type'] instanceof CompteTypeEnum) {
                $this->type = $data['type'];
            } else {
                $typeValue = strtolower(trim($data['type']));
                if (!in_array($typeValue, ['epargne', 'cheque', 'courant'])) {
                    throw new \InvalidArgumentException("Invalid type: {$typeValue}. Must be one of: epargne, cheque, courant");
                }
                $this->type = CompteTypeEnum::from($typeValue);
            }
        }

        // Convert devise
        if (isset($data['devise'])) {
            if ($data['devise'] instanceof DeviseEnum) {
                $this->devise = $data['devise'];
            } else {
                $deviseValue = strtoupper(trim($data['devise']));
                if (!in_array($deviseValue, ['XOF', 'EUR', 'USD'])) {
                    throw new \InvalidArgumentException("Invalid devise: {$deviseValue}. Must be one of: XOF, EUR, USD");
                }
                $this->devise = DeviseEnum::from($deviseValue);
            }
        }

        // Convert statut
        if (isset($data['statut'])) {
            if ($data['statut'] instanceof StatutEnum) {
                $this->statut = $data['statut'];
            } else {
                $statutValue = strtolower(trim($data['statut']));
                if (!in_array($statutValue, ['actif', 'bloque', 'ferme'])) {
                    throw new \InvalidArgumentException("Invalid statut: {$statutValue}. Must be one of: actif, bloque, ferme");
                }
                $this->statut = StatutEnum::from($statutValue);
            }
        }

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