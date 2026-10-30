<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display the application welcome page.
     */
    public function index()
    {
        return view('welcome');
    }
}
