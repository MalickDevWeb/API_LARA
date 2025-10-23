<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\DTOs\CreateTransactionDto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $transactions = $this->transactionService->getAllTransactions($perPage);
        return response()->json($transactions);
    }

    public function show(string $transaction): JsonResponse
    {
        $transaction = $this->transactionService->getTransactionById($transaction);
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        return response()->json($transaction);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $dto = new CreateTransactionDto($request->all());
            $transaction = $this->transactionService->createTransaction($dto);
            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, string $transaction): JsonResponse
    {
        try {
            $transaction = $this->transactionService->updateTransaction($transaction, $request->all());
            return response()->json($transaction);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(string $transaction): JsonResponse
    {
        try {
            $this->transactionService->deleteTransaction($transaction);
            return response()->json(['message' => 'Transaction deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}