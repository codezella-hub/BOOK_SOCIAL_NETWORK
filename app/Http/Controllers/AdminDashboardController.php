<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        // Données statistiques pour le dashboard


        return view('admin.dashboard');
    }
}
