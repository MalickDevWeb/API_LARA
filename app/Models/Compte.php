<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'solde',
        'devise',
        'statut',
        'motif_blocage',
        'metadata',
    ];

    protected $casts = [
        'solde' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'titulaire_id', 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'compte_id');
    }
}