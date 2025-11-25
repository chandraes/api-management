<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah user
        $totalUsers = User::count();

        // Hitung endpoint API saja
        $apiRoutes = collect(Route::getRoutes())->filter(function ($route) {
            return str_starts_with($route->uri(), 'api/');
        });

        $totalApiEndpoints = $apiRoutes->count();

        return view('dashboard', [
            'totalUsers'        => $totalUsers,
            'totalApiEndpoints' => $totalApiEndpoints,
        ]);
    }
}

