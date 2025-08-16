<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
//use App\Controller\BaseController;
use App\Models\LibrosModel;
class Libros extends BaseController{

protected $helpers = ['form'];

public function index()
{
    return view('Administrador/libros');
}

public function new()
    {
        return view('Administrador/Libros/nuevo');
        
    }

}