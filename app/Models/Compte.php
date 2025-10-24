<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionTypeEnum;

/**
 * @OA\Schema(
 *     schema="Compte",
 *     type="object",
 *     title="Compte",
 *     description="Modèle de compte",
 *     @OA\Property(property="id", type="string", description="ID du compte"),
 *     @OA\Property(property="numero_compte", type="string", description="Numéro du compte"),
 *     @OA\Property(property="titulaire_id", type="string", description="ID du titulaire"),
 *     @OA\Property(property="type", type="string", enum={"courant", "epargne"}, description="Type de compte"),
 *     @OA\Property(property="devise", type="string", enum={"XOF", "EUR", "USD"}, description="Devise du compte"),
 *     @OA\Property(property="statut", type="string", enum={"actif", "bloque", "ferme"}, description="Statut du compte"),
 *     @OA\Property(property="motif_blocage", type="string", description="Motif de blocage"),
 *     @OA\Property(property="metadata", type="object", description="Métadonnées"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Compte extends Model
{
    use HasFactory;

    protected $table = 'comptes';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'numero_compte',
        'titulaire_id',
        'type',
        'devise',
        'statut',
        'motif_blocage',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'type' => \App\Enums\CompteTypeEnum::class,
        'devise' => \App\Enums\DeviseEnum::class,
        'statut' => \App\Enums\StatutEnum::class,
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'titulaire_id', 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'compte_id');
    }

    public function getSoldeAttribute()
    {
        return $this->transactions()
            ->where('statut', 'actif')
            ->get()
            ->sum(function ($transaction) {
                return $transaction->type === TransactionTypeEnum::DEPOT ? $transaction->montant : -$transaction->montant;
            });
    }
}