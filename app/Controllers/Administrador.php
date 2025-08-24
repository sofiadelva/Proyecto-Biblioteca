<?php

namespace App\Controllers;

class Administrador extends BaseController
{
    public function panel()
    {
        return view('Administrador/panel'); // 👈 busca en app/Views/Administrador/panel.php
    }

    public function libros()
    {
        // cuando entres a libros, cargas directamente la vista
        return view('Administrador/Libros/libros');
    }
}
