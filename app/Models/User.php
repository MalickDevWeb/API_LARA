<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les colonnes qu’on peut remplir automatiquement avec create() ou update().
     */
    protected $fillable = [
        'prenom',
        'nom',
        'sexe',
        'date_naissance',
        'adresse',
        'login',
        'email',
        'telephone',
        'type',
        'password',
    ];

    /**
     * Les attributs cachés lors de la sérialisation (par ex. quand on retourne un JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs à caster automatiquement.
     */
    protected $casts = [
        'date_naissance' => 'date',
    ];

    /**
     * Mutator : toujours hasher le mot de passe automatiquement
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Relationship with Client
     */
    public function client()
    {
        return $this->hasOne(Client::class, 'user_id');
    }

    /**
     * Relationship with Admin
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->type === 'ADMIN';
    }

    /**
     * Check if user is client
     */
    public function isClient(): bool
    {
        return $this->type === 'CLIENT';
    }
}
