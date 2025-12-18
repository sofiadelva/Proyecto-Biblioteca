<?php

namespace App\Controllers;

class GestionLibros extends BaseController
{
    public function index()
    {
        return view('Administrador/Gestion/index'); // Muestra la view de las gestión de libros del bibliotecario.
    }
}
