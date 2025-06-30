<?php

declare(strict_types=1);

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Services\UserFarmService;
use App\Structures\FarmStructure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class FarmController extends Controller
{
    public function __construct(
        private readonly UserFarmService $userFarmService
    ) {
    }

    public function getFarmsWithSpace(Request $request): JsonResponse
    {
        $farms = $this->userFarmService->getUserFarmsWithSpace($request->user());

        return response()->json(['farms' => $farms]);
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
        ]);

        $structure = new FarmStructure();
        $structure->name = $request->input('name');
        $structure->email = $request->input('email');
        $structure->website = $request->input('website');

        $this->userFarmService->createUserFarm($request->user(), $structure);

        return response()->json(['success' => true]);
    }
}
