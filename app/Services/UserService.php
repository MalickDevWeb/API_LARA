<?php

namespace App\Services;

use App\Models\User;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\DTOs\CreateUserDto;
use App\Enums\ErrorEnum;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class UserService implements UserServiceInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->findAll($perPage);
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function getUserByLogin(string $login): ?User
    {
        return $this->userRepository->findByLogin($login);
    }

    public function createUser(CreateUserDto $dto): User
    {
        $validator = Validator::make((array) $dto, CreateUserDto::rules());
        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return $this->userRepository->create($dto);
    }

    public function updateUser(int $id, array $data): User
    {
        $validator = Validator::make($data, [
            'prenom' => 'sometimes|string|max:255',
            'nom' => 'sometimes|string|max:255',
            'sexe' => 'sometimes|in:M,F',
            'date_naissance' => 'sometimes|date|before:today',
            'adresse' => 'sometimes|string',
            'login' => 'sometimes|string|unique:users,login,' . $id,
            'email' => 'sometimes|string|email|unique:users,email,' . $id,
            'telephone' => 'sometimes|string',
            'type' => 'sometimes|in:CLIENT,ADMIN',
            'password' => 'sometimes|string|min:8',
        ]);
        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new \Exception(ErrorEnum::USER_NOT_FOUND->value);
        }

        // Business logic: Check if user has accounts
        $hasAccounts = \App\Models\Client::where('user_id', $id)->exists();
        if ($hasAccounts) {
            throw new \Exception(ErrorEnum::CANNOT_DELETE_USER_WITH_ACCOUNTS->value);
        }

        return $this->userRepository->delete($id);
    }
}