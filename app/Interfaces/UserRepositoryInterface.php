<?php

namespace App\Interfaces;

use App\Models\User;
use App\DTOs\CreateUserDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findAll(int $perPage = 15): LengthAwarePaginator;

    public function findByEmail(string $email): ?User;

    public function findByLogin(string $login): ?User;

    public function create(CreateUserDto $dto): User;

    public function update(int $id, array $data): User;

    public function delete(int $id): bool;
}