<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Services\DashboardService;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => $this->dashboardService->stats(),
            'topScorers' => $this->dashboardService->topScorers(),
            'recentAttempts' => $this->dashboardService->recentAttempts(),
        ]);
    }
}
