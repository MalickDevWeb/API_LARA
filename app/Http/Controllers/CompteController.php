<?php

namespace App\Http\Controllers;

use App\Services\CompteService;
use App\DTOs\CreateCompteDto;
use App\DTOs\UpdateCompteDto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompteController extends Controller
{
    protected CompteService $compteService;

    public function __construct(CompteService $compteService)
    {
        $this->compteService = $compteService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $comptes = $this->compteService->getAllComptes($perPage);
        return response()->json($comptes);
    }

    public function show(string $compte): JsonResponse
    {
        $compte = $this->compteService->getCompteById($compte);
        if (!$compte) {
            return response()->json(['error' => 'Compte not found'], 404);
        }
        return response()->json($compte);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $dto = new CreateCompteDto($request->all());
            $compte = $this->compteService->createCompte($dto);
            return response()->json($compte, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, string $compte): JsonResponse
    {
        try {
            $dto = new UpdateCompteDto($request->all());
            $compte = $this->compteService->updateCompte($compte, $dto);
            return response()->json($compte);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(string $compte): JsonResponse
    {
        try {
            $this->compteService->deleteCompte($compte);
            return response()->json(['message' => 'Compte deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function bloquer(Request $request, string $compte): JsonResponse
    {
        try {
            $motif = $request->get('motif', 'Blocked by admin');
            $compte = $this->compteService->bloquerCompte($compte, $motif);
            return response()->json($compte);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function debloquer(string $compte): JsonResponse
    {
        try {
            $compte = $this->compteService->debloquerCompte($compte);
            return response()->json($compte);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
