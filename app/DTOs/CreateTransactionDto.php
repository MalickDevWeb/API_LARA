<?php

namespace App\DTOs;

use App\Enums\TransactionTypeEnum;
use App\Enums\DeviseEnum;
use App\Enums\StatutEnum;

/**
 * @OA\Schema(
 *     schema="CreateTransactionDto",
 *     type="object",
 *     title="CreateTransactionDto",
 *     description="DTO pour créer une transaction",
 *     required={"compte_id", "type", "montant", "devise"},
 *     @OA\Property(property="compte_id", type="string", description="ID du compte"),
 *     @OA\Property(property="type", type="string", enum={"depot", "retrait"}, description="Type de transaction"),
 *     @OA\Property(property="montant", type="number", format="float", description="Montant de la transaction"),
 *     @OA\Property(property="devise", type="string", enum={"XOF", "EUR", "USD"}, description="Devise de la transaction"),
 *     @OA\Property(property="description", type="string", description="Description de la transaction"),
 *     @OA\Property(property="date_transaction", type="string", format="date-time", description="Date de la transaction"),
 *     @OA\Property(property="statut", type="string", enum={"actif", "bloque", "ferme"}, description="Statut de la transaction")
 * )
 */
class CreateTransactionDto
{
    public string $compte_id;
    public TransactionTypeEnum $type;
    public float $montant;
    public DeviseEnum $devise;
    public ?string $description = null;
    public ?string $date_transaction = null;
    public StatutEnum $statut;

    public function __construct(array $data)
    {
        $this->compte_id = $data['compte_id'];

        // Convert type
        if ($data['type'] instanceof TransactionTypeEnum) {
            $this->type = $data['type'];
        } else {
            $typeValue = strtolower(trim($data['type']));
            if (!in_array($typeValue, ['depot', 'retrait'])) {
                throw new \InvalidArgumentException("Invalid type: {$typeValue}. Must be one of: depot, retrait");
            }
            $this->type = TransactionTypeEnum::from($typeValue);
        }

        $this->montant = $data['montant'];

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

        $this->description = $data['description'] ?? null;
        $this->date_transaction = $data['date_transaction'] ?? now()->toDateTimeString();

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
        } else {
            $this->statut = StatutEnum::from('actif');
        }
    }

    public static function rules(): array
    {
        return [
            'compte_id' => 'required|string|exists:comptes,id',
            'type' => 'required|in:' . implode(',', array_column(TransactionTypeEnum::cases(), 'value')),
            'montant' => 'required|numeric|min:0.01',
            'devise' => 'required|in:' . implode(',', array_column(DeviseEnum::cases(), 'value')),
            'description' => 'nullable|string',
            'date_transaction' => 'nullable|date',
            'statut' => 'sometimes|in:' . implode(',', array_column(StatutEnum::cases(), 'value')),
        ];
    }
}