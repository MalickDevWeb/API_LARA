<?php

namespace App\Services;

use App\Models\Transaction;
use App\Interfaces\TransactionServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\DTOs\CreateTransactionDto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionService implements TransactionServiceInterface
{
    protected TransactionRepositoryInterface $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function getAllTransactions(int $perPage = 15): LengthAwarePaginator
    {
        return $this->transactionRepository->findAll($perPage);
    }

    public function getTransactionById(string $id): ?Transaction
    {
        return $this->transactionRepository->findById($id);
    }

    public function getTransactionsByCompte(string $compteId): LengthAwarePaginator
    {
        return $this->transactionRepository->findByCompteId($compteId);
    }

    public function createTransaction(CreateTransactionDto $dto): Transaction
    {
        $validator = Validator::make((array) $dto, CreateTransactionDto::rules());
        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        // Business logic: Check if account exists and is active
        $compte = \App\Models\Compte::find($dto->compte_id);
        if (!$compte) {
            throw new \Exception('Account not found');
        }
        if ($compte->statut !== 'actif') {
            throw new \Exception('Account is not active');
        }

        // For deposits, no balance check; for withdrawals, check sufficient balance
        if ($dto->type->value === 'retrait') {
            if ($compte->solde < $dto->montant) {
                throw new \Exception('Insufficient balance');
            }
        }

        return DB::transaction(function () use ($dto, $compte) {
            $transaction = $this->transactionRepository->create($dto);

            // Update account balance
            $newBalance = $dto->type->value === 'depot' ? $compte->solde + $dto->montant : $compte->solde - $dto->montant;
            $compte->update(['solde' => $newBalance]);

            return $transaction;
        });
    }

    public function updateTransaction(string $id, array $data): Transaction
    {
        $validator = Validator::make($data, [
            'description' => 'nullable|string',
            'statut' => 'sometimes|in:actif,bloque,inactif',
        ]);
        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return $this->transactionRepository->update($id, $data);
    }

    public function deleteTransaction(string $id): bool
    {
        $transaction = $this->transactionRepository->findById($id);
        if (!$transaction) {
            throw new \Exception('Transaction not found');
        }

        // Business logic: Cannot delete if transaction affects balance
        throw new \Exception('Deleting transactions is not allowed for audit purposes');

        return $this->transactionRepository->delete($id);
    }
}