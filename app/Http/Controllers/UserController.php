<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\DTOs\CreateUserDto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $users = $this->userService->getAllUsers($perPage);
        return response()->json($users);
    }

    public function show(int $user): JsonResponse
    {
        $user = $this->userService->getUserById($user);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $dto = new CreateUserDto($request->all());
            $user = $this->userService->createUser($dto);
            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, int $user): JsonResponse
    {
        try {
            $user = $this->userService->updateUser($user, $request->all());
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $user): JsonResponse
    {
        try {
            $this->userService->deleteUser($user);
            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}