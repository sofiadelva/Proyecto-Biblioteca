<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('login'); // Muestra la view del login como principal.
    }
}
