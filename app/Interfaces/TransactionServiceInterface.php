<?php

namespace App\Interfaces;

use App\Models\Transaction;
use App\DTOs\CreateTransactionDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface TransactionServiceInterface
{
    public function getAllTransactions(int $perPage = 15): LengthAwarePaginator;

    public function getTransactionById(string $id): ?Transaction;

    public function getTransactionsByCompte(string $compteId): LengthAwarePaginator;

    public function createTransaction(CreateTransactionDto $dto): Transaction;

    public function updateTransaction(string $id, array $data): Transaction;

    public function deleteTransaction(string $id): bool;
}