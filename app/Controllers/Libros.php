<?php
namespace App\Controllers;

use App\Models\LibroModel;
use App\Models\CategoriaModel;
use CodeIgniter\Controller;

class Libros extends Controller
{
    protected $libroModel;
    protected $categoriaModel;

    public function __construct()
    {
        // Instanciamos los modelos
        $this->libroModel = new LibroModel();
        $this->categoriaModel = new CategoriaModel();
    }

    // Listar todos los libros
    public function index()
{
    // Obtener parámetros GET
    $ordenar = $this->request->getGet('ordenar');
    $estado = $this->request->getGet('estado');
    $cantidad_disponible = $this->request->getGet('cantidad_disponible');

    // Traemos libros con JOIN para obtener el nombre de la categoría
    $builder = $this->libroModel
        ->select('libros.*, categorias.nombre as categoria')
        ->join('categorias', 'categorias.categoria_id = libros.categoria_id');

    // Filtrar por estado
    if ($estado) {
        $builder = $builder->where('estado', $estado);
    }

    // Filtrar por cantidad disponible
    if ($cantidad_disponible !== '' && $cantidad_disponible !== null) {
        if ($cantidad_disponible == '0') {
            $builder = $builder->where('cantidad_disponibles', 0);
        } else {
            $builder = $builder->where('cantidad_disponibles >', 0);
        }
    }

    // Ordenar
    if ($ordenar) {
        switch ($ordenar) {
            case 'titulo_asc':
                $builder = $builder->orderBy('titulo', 'ASC');
                break;
            case 'titulo_desc':
                $builder = $builder->orderBy('titulo', 'DESC');
                break;
            case 'autor_asc':
                $builder = $builder->orderBy('autor', 'ASC');
                break;
            case 'autor_desc':
                $builder = $builder->orderBy('autor', 'DESC');
                break;
            case 'reciente':
                $builder = $builder->orderBy('libro_id', 'DESC'); // más reciente primero
                break;
            case 'viejo':
                $builder = $builder->orderBy('libro_id', 'ASC'); // más viejo primero
                break;
        }
    }

    $data['libros'] = $builder->findAll();

    // Mostramos la vista
    return view('Administrador/libros', $data);
}



    // Mostrar formulario de nuevo libro
    public function new()
    {
        // Pasamos las categorías para llenar el <select>
        $data['categorias'] = $this->categoriaModel->findAll();
        return view('Administrador/Libros/nuevo', $data);
    }

    // Guardar libro en la base de datos
    public function create()
    {
        $this->libroModel->insert([
            'titulo' => $this->request->getPost('titulo'),
            'autor'  => $this->request->getPost('autor'),
            'editorial' => $this->request->getPost('editorial'),
            'cantidad_total' => $this->request->getPost('cantidad_total'),
            'cantidad_disponibles' => $this->request->getPost('cantidad_disponibles'),
            'estado' => $this->request->getPost('estado'),
            'categoria_id' => $this->request->getPost('categoria_id') // 👈 guardamos el id de la categoría
        ]);

        return redirect()->to(base_url('libros'));
    }

    // Mostrar formulario de edición de un libro
    public function edit($id)
    {
        $data['libro'] = $this->libroModel->find($id);           // libro actual
        $data['categorias'] = $this->categoriaModel->findAll();  // todas las categorías
        return view('Administrador/Libros/edit', $data);
    }

    // Actualizar un libro
    public function update($id)
    {
        $this->libroModel->update($id, [
            'titulo' => $this->request->getPost('titulo'),
            'autor'  => $this->request->getPost('autor'),
            'editorial' => $this->request->getPost('editorial'),
            'cantidad_total' => $this->request->getPost('cantidad_total'),
            'cantidad_disponibles' => $this->request->getPost('cantidad_disponibles'),
            'estado' => $this->request->getPost('estado'),
            'categoria_id' => $this->request->getPost('categoria_id')
        ]);
        return redirect()->to(base_url('libros'));
    }

    public function delete($id = null)
{
    $libroModel = new LibroModel();

    // Verifica que el libro existe
    $libro = $libroModel->find($id);
    if (!$libro) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException("El libro con ID $id no existe");
    }

    // Elimina el libro
    $libroModel->delete($id);

    // Redirige con mensaje de éxito
    return redirect()->to(base_url('libros'))->with('msg', 'Libro eliminado correctamente.');
}
}
