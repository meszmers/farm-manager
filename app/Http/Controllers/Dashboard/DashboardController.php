<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserFarmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly UserFarmService $userFarmService
    ) {
    }

    public function welcome(Request $request): RedirectResponse|Response
    {
        if ($request->user()) {
            return redirect()->route('farms.index');
        }

        return Inertia::render('welcome');
    }

    public function index(Request $request): Response
    {
        $farms = $this->userFarmService->getPaginatedUserFarms($request->user());

        return Inertia::render('farms', ['paginatedFarms' => $farms]);
    }

    public function createFarm(): Response
    {
        return Inertia::render('create-farm');
    }

    public function show(Request $request, int $id)
    {
        $farm = $this->userFarmService->getUserFarm($request->user(), $id);

        return Inertia::render('farm', ['farm' => $farm]);
    }
}
