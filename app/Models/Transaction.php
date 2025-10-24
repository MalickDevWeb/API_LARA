<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Transaction",
 *     type="object",
 *     title="Transaction",
 *     description="Modèle de transaction",
 *     @OA\Property(property="id", type="string", description="ID de la transaction"),
 *     @OA\Property(property="compte_id", type="string", description="ID du compte"),
 *     @OA\Property(property="type", type="string", enum={"depot", "retrait"}, description="Type de transaction"),
 *     @OA\Property(property="montant", type="number", format="float", description="Montant de la transaction"),
 *     @OA\Property(property="devise", type="string", enum={"XOF", "EUR", "USD"}, description="Devise de la transaction"),
 *     @OA\Property(property="description", type="string", description="Description de la transaction"),
 *     @OA\Property(property="date_transaction", type="string", format="date-time", description="Date de la transaction"),
 *     @OA\Property(property="statut", type="string", enum={"actif", "bloque", "ferme"}, description="Statut de la transaction"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'compte_id',
        'type',
        'montant',
        'devise',
        'description',
        'date_transaction',
        'statut',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_transaction' => 'datetime',
        'type' => \App\Enums\TransactionTypeEnum::class,
        'devise' => \App\Enums\DeviseEnum::class,
        'statut' => \App\Enums\StatutEnum::class,
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class, 'compte_id');
    }
}