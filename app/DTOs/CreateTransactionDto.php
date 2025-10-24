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
        $this->type = $data['type'];
        $this->montant = $data['montant'];
        $this->devise = $data['devise'];
        $this->description = $data['description'] ?? null;
        $this->date_transaction = $data['date_transaction'] ?? now()->toDateTimeString();
        $this->statut = $data['statut'] ?? 'actif';
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