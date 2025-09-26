<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function home()
    {
        // Données statistiques pour le dashboard


        return view('user.home');
    }
}
