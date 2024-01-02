<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
        {
            // return view('content.apps.app-logistics-dashboard');
            return view('content.dashboard.dashboards');
        }
}
