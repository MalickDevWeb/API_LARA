<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\DTOs\CreateUserDto;
use App\Enums\ErrorEnum;
use App\Enums\HttpStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="SugarCRM API",
 *     version="1.0.0",
 *     description="Documentation complète des endpoints SugarCRM",
 *     @OA\Contact(
 *         name="Ton Nom",
 *         email="tonemail@example.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="https://api.monsugarcrm.com",
 *     description="Production server"
 * )
 *
 * @OA\Server(
 *     url="https://sandbox.monsugarcrm.com",
 *     description="Test/Sandbox server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/v1/users",
     *     summary="Liste tous les utilisateurs",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des utilisateurs",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $users = $this->userService->getAllUsers($perPage);
        return response()->json($users);
    }

    /**
     * @OA\Get(
     *     path="/v1/users/{userId}",
     *     summary="Détail d'un utilisateur",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'utilisateur",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé"
     *     )
     * )
     */
    public function show(int $user): JsonResponse
    {
        $user = $this->userService->getUserById($user);
        if (!$user) {
            return response()->json(['error' => ErrorEnum::USER_NOT_FOUND->value], HttpStatusEnum::NOT_FOUND->value);
        }
        return response()->json($user);
    }

    /**
     * @OA\Post(
     *     path="/v1/users",
     *     summary="Créer un utilisateur",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateUserDto")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur créé",
     *         @OA\JsonContent(ref="#/components/schemas/User")
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
            $dto = new CreateUserDto($request->all());
            $user = $this->userService->createUser($dto);
            return response()->json($user, HttpStatusEnum::CREATED->value);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], HttpStatusEnum::BAD_REQUEST->value);
        }
    }

    /**
     * @OA\Put(
     *     path="/v1/users/{userId}",
     *     summary="Modifier un utilisateur",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateUserDto")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur modifié",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function update(Request $request, int $user): JsonResponse
    {
        try {
            $user = $this->userService->updateUser($user, $request->all());
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], HttpStatusEnum::BAD_REQUEST->value);
        }
    }

    /**
     * @OA\Delete(
     *     path="/v1/users/{userId}",
     *     summary="Supprimer un utilisateur",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur supprimé"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function destroy(int $user): JsonResponse
    {
        try {
            $this->userService->deleteUser($user);
            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], HttpStatusEnum::BAD_REQUEST->value);
        }
    }
}
