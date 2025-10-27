<?php

namespace App\Http\Controllers;

use App\Services\CompteService;
use App\DTOs\CreateCompteDto;
use App\DTOs\UpdateCompteDto;
use App\Enums\ErrorEnum;
use App\Enums\HttpStatusEnum;
use App\Traits\ExceptionHandlerTrait;
use App\Traits\HandlesApiException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompteController extends Controller
{
    use ExceptionHandlerTrait, HandlesApiException;

    protected CompteService $compteService;

    public function __construct(CompteService $compteService)
    {
        $this->compteService = $compteService;
    }

/**
 * @OA\Get(
 *     path="/v1/comptes",
 *     summary="Liste tous les comptes",
 *     tags={"Comptes"},
 *     @OA\Response(
 *         response=200,
 *         description="Liste des comptes",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Compte")
 *         )
 *     )
 * )
 */

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $comptes = $this->compteService->getAllComptes($perPage);
        return response()->json($comptes);
    }

/**
 * @OA\Get(
 *     path="/v1/comptes/{compteId}",
 *     summary="Détail d'un compte",
 *     tags={"Comptes"},
 *     @OA\Parameter(
 *         name="compteId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Détails du compte",
 *         @OA\JsonContent(ref="#/components/schemas/Compte")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Compte non trouvé"
 *     )
 * )
 */

    public function show(string $compteId): JsonResponse
    {
        $compte = $this->compteService->getCompteById($compteId);
        if (!$compte) {
            return response()->json(['error' => ErrorEnum::COMPTE_NOT_FOUND->value], HttpStatusEnum::NOT_FOUND->value);
        }
        return response()->json($compte);
    }

/**
 * @OA\Post(
 *     path="/v1/comptes",
 *     summary="Créer un compte",
 *     tags={"Comptes"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/CreateCompteDto")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Compte créé",
 *         @OA\JsonContent(ref="#/components/schemas/Compte")
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
            // Validate request data first
            $rules = CreateCompteDto::rules();
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'details' => $validator->errors()
                ], HttpStatusEnum::BAD_REQUEST->value);
            }
            $data = $request->all();
            \Log::info('Creating compte with data:', $data);
            $dto = new CreateCompteDto($data);
            $compte = $this->compteService->createCompte($dto);
            return response()->json($compte, HttpStatusEnum::CREATED->value);
        } catch (\Throwable $e) {
            return $this->handleApiException($e);
        }
    }

/**
 * @OA\Put(
 *     path="/v1/comptes/{compteId}",
 *     summary="Modifier un compte",
 *     tags={"Comptes"},
 *     @OA\Parameter(
 *         name="compteId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/UpdateCompteDto")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Compte modifié",
 *         @OA\JsonContent(ref="#/components/schemas/Compte")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erreur de validation"
 *     )
 * )
 */

    public function update(Request $request, string $compteId): JsonResponse
    {
        try {
            $dto = new UpdateCompteDto($request->all());
            $compte = $this->compteService->updateCompte($compteId, $dto);
            return response()->json($compte);
        } catch (\Throwable $e) {
            return $this->handleApiException($e);
        }
    }

/**
 * @OA\Delete(
 *     path="/v1/comptes/{compteId}",
 *     summary="Supprimer un compte",
 *     tags={"Comptes"},
 *     @OA\Parameter(
 *         name="compteId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Compte supprimé"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erreur de validation"
 *     )
 * )
 */

    public function destroy(string $compteId): JsonResponse
    {
        try {
            $this->compteService->deleteCompte($compteId);
            return response()->json(['message' => 'Compte deleted successfully']);
        } catch (\Throwable $e) {
            return $this->handleApiException($e);
        }
    }

/**
 * @OA\Post(
 *     path="/v1/comptes/{compteId}/bloquer",
 *     summary="Bloquer un compte",
 *     tags={"Comptes"},
 *     @OA\Parameter(
 *         name="compteId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="motif", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Compte bloqué",
 *         @OA\JsonContent(ref="#/components/schemas/Compte")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erreur de validation"
 *     )
 * )
 */

    public function bloquer(Request $request, string $compteId): JsonResponse
    {
        try {
            $motif = $request->get('motif', 'Blocked by admin');
            $compte = $this->compteService->bloquerCompte($compteId, $motif);
            return response()->json($compte);
        } catch (\Throwable $e) {
            return $this->handleApiException($e);
        }
    }

/**
 * @OA\Post(
 *     path="/v1/comptes/{compteId}/debloquer",
 *     summary="Débloquer un compte",
 *     tags={"Comptes"},
 *     @OA\Parameter(
 *         name="compteId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Compte débloqué",
 *         @OA\JsonContent(ref="#/components/schemas/Compte")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erreur de validation"
 *     )
 * )
 */

    public function debloquer(string $compteId): JsonResponse
    {
        try {
            $compte = $this->compteService->debloquerCompte($compteId);
            return response()->json($compte);
        } catch (\Throwable $e) {
            return $this->handleApiException($e);
        }
    }
}
