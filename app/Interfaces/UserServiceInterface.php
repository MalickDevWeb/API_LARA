<?php

namespace App\Interfaces;

use App\Models\User;
use App\DTOs\CreateUserDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getAllUsers(int $perPage = 15): LengthAwarePaginator;

    public function getUserById(int $id): ?User;

    public function getUserByEmail(string $email): ?User;

    public function getUserByLogin(string $login): ?User;

    public function createUser(CreateUserDto $dto): User;

    public function updateUser(int $id, array $data): User;

    public function deleteUser(int $id): bool;
}