<?php

namespace App\Interfaces;

use App\Models\Transaction;
use App\DTOs\CreateTransactionDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface
{
    public function findById(string $id): ?Transaction;

    public function findAll(int $perPage = 15): LengthAwarePaginator;

    public function findByCompteId(string $compteId): LengthAwarePaginator;

    public function create(CreateTransactionDto $dto): Transaction;

    public function update(string $id, array $data): Transaction;

    public function delete(string $id): bool;
}