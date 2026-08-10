<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $recentUsers = User::where('last_login_at', '>=', now()->subDays(7))
                           ->orderBy('last_login_at', 'desc')
                           ->paginate(10);

        return view('admin.dashboard', compact('totalUsers', 'recentUsers'));
    }
}