<?php

namespace App\DTOs;

use App\Enums\CompteTypeEnum;
use App\Enums\DeviseEnum;
use App\Enums\StatutEnum;

/**
 * @OA\Schema(
 *     schema="CreateCompteDto",
 *     type="object",
 *     title="CreateCompteDto",
 *     description="DTO pour créer un compte",
 *     required={"numero_compte", "titulaire_id", "type", "devise"},
 *     @OA\Property(property="numero_compte", type="string", description="Numéro du compte"),
 *     @OA\Property(property="titulaire_id", type="integer", description="ID du titulaire"),
 *     @OA\Property(property="type", type="string", enum={"courant", "epargne"}, description="Type de compte"),
 *     @OA\Property(property="devise", type="string", enum={"XOF", "EUR", "USD"}, description="Devise du compte"),
 *     @OA\Property(property="statut", type="string", enum={"actif", "bloque", "ferme"}, description="Statut du compte"),
 *     @OA\Property(property="motif_blocage", type="string", description="Motif de blocage"),
 *     @OA\Property(property="metadata", type="object", description="Métadonnées")
 * )
 */
class CreateCompteDto
{
    use \App\Traits\EnumHandler;
    

    private string $numero_compte;
    private int $titulaire_id;
    private CompteTypeEnum $type;
    private DeviseEnum $devise;
    private StatutEnum $statut;
    private ?string $motif_blocage = null;
    private ?array $metadata = null;

    public function __construct(array $data)
    {
        try {
            $this->numero_compte = trim($data['numero_compte']);
            $this->titulaire_id = (int)$data['titulaire_id'];

            // Convert type
            if ($data['type'] instanceof CompteTypeEnum) {
                $this->type = $data['type'];
            } else {
                $typeValue = strtolower(trim($data['type']));
                if (!in_array($typeValue, ['epargne', 'cheque', 'courant'])) {
                    throw new \InvalidArgumentException("Invalid type: {$typeValue}. Must be one of: epargne, cheque, courant");
                }
                $this->type = CompteTypeEnum::from($typeValue);
            }
            
            // Convert devise
            if ($data['devise'] instanceof DeviseEnum) {
                $this->devise = $data['devise'];
            } else {
                $deviseValue = strtoupper(trim($data['devise']));
                if (!in_array($deviseValue, ['XOF', 'EUR', 'USD'])) {
                    throw new \InvalidArgumentException("Invalid devise: {$deviseValue}. Must be one of: XOF, EUR, USD");
                }
                $this->devise = DeviseEnum::from($deviseValue);
            }
            
            // Convert statut if provided, otherwise use default
            $statutValue = isset($data['statut']) ? trim($data['statut']) : 'actif';
            if ($statutValue instanceof StatutEnum) {
                $this->statut = $statutValue;
            } else {
                $statutValue = strtolower($statutValue);
                if (!in_array($statutValue, ['actif', 'bloque', 'ferme'])) {
                    throw new \InvalidArgumentException("Invalid statut: {$statutValue}. Must be one of: actif, bloque, ferme");
                }
                $this->statut = StatutEnum::from($statutValue);
            }
            
            $this->motif_blocage = $data['motif_blocage'] ?? null;
            $this->metadata = $data['metadata'] ?? null;
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException('Error creating compte: ' . $e->getMessage());
        }
    }

    public function toArray(): array
    {
        return [
            'numero_compte' => $this->numero_compte,
            'titulaire_id' => $this->titulaire_id,
            'type' => $this->type->value,
            'devise' => $this->devise->value,
            'statut' => $this->statut->value,
            'motif_blocage' => $this->motif_blocage,
            'metadata' => $this->metadata,
        ];
    }

    // Getters
    public function getNumeroCompte(): string { return $this->numero_compte; }
    public function getTitulaireId(): int { return $this->titulaire_id; }
    public function getType(): CompteTypeEnum { return $this->type; }
    public function getDevise(): DeviseEnum { return $this->devise; }
    public function getStatut(): StatutEnum { return $this->statut; }
    public function getMotifBlocage(): ?string { return $this->motif_blocage; }
    public function getMetadata(): ?array { return $this->metadata; }

    public static function rules(): array
    {
        return [
            'numero_compte' => 'required|string|unique:comptes,numero_compte',
            'titulaire_id' => 'required|integer|exists:client,user_id',
            'type' => 'required|in:' . implode(',', array_column(CompteTypeEnum::cases(), 'value')),
            'devise' => 'required|in:' . implode(',', array_column(DeviseEnum::cases(), 'value')),
            'statut' => 'sometimes|in:' . implode(',', array_column(StatutEnum::cases(), 'value')),
            'motif_blocage' => 'nullable|string',
            'metadata' => 'nullable|array',
        ];
    }
}