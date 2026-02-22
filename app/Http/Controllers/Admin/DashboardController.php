<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $totalUsers = User::count();
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();
        $adminCount = $adminRole ? $adminRole->users()->count() : 0;
        $userCount = $userRole ? $userRole->users()->count() : 0;

        $newUsersThisWeek = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        $recentActivityLogs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get user growth data for chart (last 30 days)
        $userGrowthData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->startOfDay();
            $count = User::where('created_at', '<=', $date)->count();
            $userGrowthData[] = [
                'date' => $date->format('M d'),
                'count' => $count,
            ];
        }

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'adminCount' => $adminCount,
            'userCount' => $userCount,
            'newUsersThisWeek' => $newUsersThisWeek,
            'recentActivityLogs' => $recentActivityLogs,
            'userGrowthData' => $userGrowthData,
        ]);
    }
}
