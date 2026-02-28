<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class StaffDashboardController extends Controller
{
    public function index()
    {
        return view('dashboards.staff');
    }
}