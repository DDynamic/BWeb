<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Get the dashboard.
     *
     * @return view
     */
    public function getDashboard(Request $request)
    {
        return view('dashboard');
    }

}
