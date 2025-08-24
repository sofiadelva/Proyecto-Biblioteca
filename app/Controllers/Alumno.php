<?php

namespace App\Controllers;

class Alumno extends BaseController
{
    public function panel()
    {
        return view('Alumno/panel'); // 👈 app/Views/Alumno/panel.php
    }
}
