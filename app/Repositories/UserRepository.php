<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use App\DTOs\CreateUserDto;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findAll(int $perPage = 15): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByLogin(string $login): ?User
    {
        return User::where('login', $login)->first();
    }

    public function create(CreateUserDto $dto): User
    {
        return User::create([
            'prenom' => $dto->prenom,
            'nom' => $dto->nom,
            'sexe' => $dto->sexe,
            'date_naissance' => $dto->date_naissance,
            'adresse' => $dto->adresse,
            'login' => $dto->login,
            'email' => $dto->email,
            'telephone' => $dto->telephone,
            'type' => $dto->type,
            'password' => $dto->password,
        ]);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->findById($id);
        if (!$user) {
            throw new \Exception('User not found');
        }
        $user->update($data);
        return $user->fresh();
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);
        if (!$user) {
            return false;
        }
        return $user->delete();
    }
}