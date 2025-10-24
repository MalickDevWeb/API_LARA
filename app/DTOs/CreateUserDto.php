<?php

namespace App\DTOs;

/**
 * @OA\Schema(
 *     schema="CreateUserDto",
 *     type="object",
 *     title="CreateUserDto",
 *     description="DTO pour créer un utilisateur",
 *     required={"prenom", "nom", "login", "email", "type", "password"},
 *     @OA\Property(property="prenom", type="string", description="Prénom de l'utilisateur"),
 *     @OA\Property(property="nom", type="string", description="Nom de l'utilisateur"),
 *     @OA\Property(property="sexe", type="string", enum={"M", "F"}, description="Sexe de l'utilisateur"),
 *     @OA\Property(property="date_naissance", type="string", format="date", description="Date de naissance"),
 *     @OA\Property(property="adresse", type="string", description="Adresse de l'utilisateur"),
 *     @OA\Property(property="login", type="string", description="Login de l'utilisateur"),
 *     @OA\Property(property="email", type="string", format="email", description="Email de l'utilisateur"),
 *     @OA\Property(property="telephone", type="string", description="Téléphone de l'utilisateur"),
 *     @OA\Property(property="type", type="string", enum={"CLIENT", "ADMIN"}, description="Type d'utilisateur"),
 *     @OA\Property(property="password", type="string", description="Mot de passe de l'utilisateur")
 * )
 */
class CreateUserDto
{
    public string $prenom;
    public string $nom;
    public ?string $sexe = null;
    public ?string $date_naissance = null;
    public ?string $adresse = null;
    public string $login;
    public string $email;
    public ?string $telephone = null;
    public string $type;
    public string $password;

    public function __construct(array $data)
    {
        $this->prenom = $data['prenom'];
        $this->nom = $data['nom'];
        $this->sexe = $data['sexe'] ?? null;
        $this->date_naissance = $data['date_naissance'] ?? null;
        $this->adresse = $data['adresse'] ?? null;
        $this->login = $data['login'];
        $this->email = $data['email'];
        $this->telephone = $data['telephone'] ?? null;
        $this->type = $data['type'];
        $this->password = $data['password'];
    }

    public static function rules(): array
    {
        return [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'sexe' => 'nullable|in:M,F',
            'date_naissance' => 'nullable|date|before:today',
            'adresse' => 'nullable|string',
            'login' => 'required|string|unique:users,login',
            'email' => 'required|string|email|unique:users,email',
            'telephone' => 'nullable|string',
            'type' => 'required|in:CLIENT,ADMIN',
            'password' => 'required|string|min:8',
        ];
    }
}