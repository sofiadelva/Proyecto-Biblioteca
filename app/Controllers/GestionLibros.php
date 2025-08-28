<?php

namespace App\Controllers;

class GestionLibros extends BaseController
{
    public function index()
    {
        return view('Bibliotecario/Gestion/index');
    }
}
