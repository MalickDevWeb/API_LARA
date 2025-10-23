<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Interfaces\TransactionRepositoryInterface;
use App\DTOs\CreateTransactionDto;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function findById(string $id): ?Transaction
    {
        return Transaction::with('compte')->find($id);
    }

    public function findAll(int $perPage = 15): LengthAwarePaginator
    {
        return Transaction::with('compte')->paginate($perPage);
    }

    public function findByCompteId(string $compteId): LengthAwarePaginator
    {
        return Transaction::with('compte')->where('compte_id', $compteId)->paginate(15);
    }

    public function create(CreateTransactionDto $dto): Transaction
    {
        return Transaction::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'compte_id' => $dto->compte_id,
            'type' => $dto->type,
            'montant' => $dto->montant,
            'devise' => $dto->devise,
            'description' => $dto->description,
            'date_transaction' => $dto->date_transaction,
            'statut' => $dto->statut,
        ]);
    }

    public function update(string $id, array $data): Transaction
    {
        $transaction = $this->findById($id);
        if (!$transaction) {
            throw new \Exception('Transaction not found');
        }
        $transaction->update($data);
        return $transaction->fresh();
    }

    public function delete(string $id): bool
    {
        $transaction = $this->findById($id);
        if (!$transaction) {
            return false;
        }
        return $transaction->delete();
    }
}