<?php

namespace App\Http\Controllers;

use App\Services\CompteService;
use App\DTOs\CreateCompteDto;
use App\DTOs\UpdateCompteDto;
use App\Enums\ErrorEnum;
use App\Enums\HttpStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompteController extends Controller
{
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

    public function show(string $compte): JsonResponse
    {
        $compte = $this->compteService->getCompteById($compte);
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
            $dto = new CreateCompteDto($request->all());
            $compte = $this->compteService->createCompte($dto);
            return response()->json($compte, HttpStatusEnum::CREATED->value);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], HttpStatusEnum::BAD_REQUEST->value);
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

    public function update(Request $request, string $compte): JsonResponse
    {
        try {
            $dto = new UpdateCompteDto($request->all());
            $compte = $this->compteService->updateCompte($compte, $dto);
            return response()->json($compte);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], HttpStatusEnum::BAD_REQUEST->value);
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

    public function destroy(string $compte): JsonResponse
    {
        try {
            $this->compteService->deleteCompte($compte);
            return response()->json(['message' => 'Compte deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], HttpStatusEnum::BAD_REQUEST->value);
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

    public function bloquer(Request $request, string $compte): JsonResponse
    {
        try {
            $motif = $request->get('motif', 'Blocked by admin');
            $compte = $this->compteService->bloquerCompte($compte, $motif);
            return response()->json($compte);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], HttpStatusEnum::BAD_REQUEST->value);
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

    public function debloquer(string $compte): JsonResponse
    {
        try {
            $compte = $this->compteService->debloquerCompte($compte);
            return response()->json($compte);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], HttpStatusEnum::BAD_REQUEST->value);
        }
    }
}
