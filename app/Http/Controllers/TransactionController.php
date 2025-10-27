<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\DTOs\CreateTransactionDto;
use App\Enums\ErrorEnum;
use App\Enums\HttpStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\HandlesApiException;

class TransactionController extends Controller
{
    use HandlesApiException;

    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @OA\Get(
     *     path="/v1/transactions",
     *     summary="Liste toutes les transactions",
     *     tags={"Transactions"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des transactions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Transaction")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $transactions = $this->transactionService->getAllTransactions($perPage);
        return response()->json($transactions);
    }

    /**
     * @OA\Get(
     *     path="/v1/transactions/{transactionId}",
     *     summary="Détail d'une transaction",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="transactionId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la transaction",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction non trouvée"
     *     )
     * )
     */
    public function show(string $transactionId): JsonResponse
    {
        $transaction = $this->transactionService->getTransactionById($transactionId);
        if (!$transaction) {
            return response()->json(['error' => ErrorEnum::TRANSACTION_NOT_FOUND->value], HttpStatusEnum::NOT_FOUND->value);
        }
        return response()->json($transaction);
    }

    /**
     * @OA\Post(
     *     path="/v1/transactions",
     *     summary="Créer une transaction",
     *     tags={"Transactions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateTransactionDto")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction créée",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $dto = new CreateTransactionDto($request->all());
            $transaction = $this->transactionService->createTransaction($dto);
            return response()->json($transaction, HttpStatusEnum::CREATED->value);
        } catch (\Throwable $e) {
            return $this->handleApiException($e);
        }
    }

    /**
     * @OA\Put(
     *     path="/v1/transactions/{transactionId}",
     *     summary="Modifier une transaction",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="transactionId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateTransactionDto")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction modifiée",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function update(Request $request, string $transactionId): JsonResponse
    {
        try {
            $transaction = $this->transactionService->updateTransaction($transactionId, $request->all());
            return response()->json($transaction);
        } catch (\Throwable $e) {
            return $this->handleApiException($e);
        }
    }

    /**
     * @OA\Delete(
     *     path="/v1/transactions/{transactionId}",
     *     summary="Supprimer une transaction",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="transactionId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction supprimée"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function destroy(string $transactionId): JsonResponse
    {
        try {
            $this->transactionService->deleteTransaction($transactionId);
            return response()->json(['message' => 'Transaction deleted successfully']);
        } catch (\Throwable $e) {
            return $this->handleApiException($e);
        }
    }
}
