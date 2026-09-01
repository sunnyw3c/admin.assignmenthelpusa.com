<?php

namespace App\Http\Controllers;

use App\Services\AdminApiService;

class DashboardController extends Controller
{
    public function index()
    {
        $api   = new AdminApiService();
        $stats = $api->getStats();
        $role  = auth()->user()->role;

        return view('dashboard.index', compact('stats', 'role'));
    }
}
