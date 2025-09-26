<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @var DashboardRepositoryInterface
     */
    protected $dashboardRepository;

    /**
     * DashboardController constructor.
     */
    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $userId = Auth::id();

        $notesCount = $this->dashboardRepository->getNotesCount($userId);
        $groupsCount = $this->dashboardRepository->getGroupsCount($userId);
        $recentNotes = $this->dashboardRepository->getRecentNotes($userId);

        return view('dashboard', compact('notesCount', 'groupsCount', 'recentNotes'));
    }
}
