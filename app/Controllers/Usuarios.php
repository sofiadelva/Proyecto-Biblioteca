<?php
namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class Usuarios extends Controller
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        helper(['form', 'url']);
    }

    /**
     * Muestra la lista de usuarios con filtros, búsqueda y paginación.
     */
    public function index()
    {
        $defaultPerPage = 10;
        
        $ordenar = $this->request->getGet('ordenar');
        $buscar = $this->request->getGet('buscar'); 
        $rolFiltro = $this->request->getGet('rol'); 
        
        $perPage = (int)($this->request->getGet('per_page') ?? $defaultPerPage); 

        if ($perPage < 1) {
            $perPage = $defaultPerPage; 
        }

        $builder = $this->usuarioModel;

        // 2. Aplicar BÚSQUEDA por nombre, correo o carne
        if ($buscar) {
            $builder = $builder->groupStart()
                               ->like('nombre', $buscar, 'both')
                               ->orLike('correo', $buscar, 'both') 
                               ->orLike('carne', $buscar, 'both') 
                               ->groupEnd();
        }

        // 3. Aplicar FILTRO por rol
        if ($rolFiltro) {
            $builder = $builder->where('rol', $rolFiltro);
        }

        // 4. Aplicar ORDENACIÓN
        if ($ordenar) {
            switch ($ordenar) {
                case 'nombre_asc':
                    $builder = $builder->orderBy('nombre', 'ASC');
                    break;
                case 'nombre_desc':
                    $builder = $builder->orderBy('nombre', 'DESC');
                    break;
                case 'reciente':
                    $builder = $builder->orderBy('usuario_id', 'DESC');
                    break;
                case 'correo_asc': 
                    $builder = $builder->orderBy('correo', 'ASC');
                    break;
                default:
                    $builder = $builder->orderBy('nombre', 'ASC');
            }
        } else {
            $builder = $builder->orderBy('nombre', 'ASC');
        }
        
        // 5. Aplicar paginación
        $data['usuarios'] = $builder->paginate($perPage, 'default');
        
        // 6. Preparar datos para la vista
        $data['pager'] = $this->usuarioModel->pager;
        $data['perPage'] = $perPage;
        $data['buscar'] = $buscar; 
        $data['rolFiltro'] = $rolFiltro;
        $data['rolesDisponibles'] = ['Administrador', 'Bibliotecario', 'Alumno', 'Docente']; 

        return view('Administrador/usuarios', $data);
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('Administrador/Usuarios/nuevo');
    }

    /**
     * Guarda el nuevo usuario en la base de datos.
     */
    public function store()
    {
        $data = $this->request->getPost();
        // Cifra la contraseña antes de guardarla (usando md5)
        $data['password'] = md5($data['password']); 
        
        if ($this->usuarioModel->insert($data)) {
            return redirect()->to(base_url('usuarios'))->with('msg', 'Usuario agregado correctamente.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
        }
    }

    /**
     * Muestra el formulario de edición de un usuario específico.
     * @param int $id ID del usuario a editar.
     */
    public function edit($id)
    {
        $data['usuario'] = $this->usuarioModel->find($id);
        if (!$data['usuario']) {
            return redirect()->to(base_url('usuarios'))->with('msg', 'Usuario no encontrado.');
        }
        return view('Administrador/Usuarios/edit', $data);
    }

    /**
     * Actualiza los datos de un usuario en la base de datos.
     * @param int $id ID del usuario a actualizar.
     */
    public function update($id)
    {
        $data = $this->request->getPost();
        
        if (!empty($data['password'])) {
            $data['password'] = md5($data['password']);
        } else {
            unset($data['password']);
        }
        
        if ($this->usuarioModel->update($id, $data)) {
            return redirect()->to(base_url('usuarios'))->with('msg', 'Usuario actualizado correctamente.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
        }
    }

    /**
     * Elimina un usuario de la base de datos.
     * @param int $id ID del usuario a eliminar.
     */
    public function delete($id)
    {
        $this->usuarioModel->delete($id);
        return redirect()->to(base_url('usuarios'))->with('msg', 'Usuario eliminado correctamente.');
    }

    /**
     * Obtener usuarios para la búsqueda dinámica (Select2).
     */
    public function getUsuariosJson()
    {
        $term = $this->request->getGet('term');
        $id = $this->request->getGet('id'); // CLAVE para la recarga del valor 'old' (carné)

        $query = $this->usuarioModel->select('usuario_id, carne, nombre');
        
        if (!empty($term)) {
            // Lógica para BÚSQUEDA DINÁMICA (Select2 envía 'term')
            $query->groupStart()
                  ->like('carne', $term)
                  ->orLike('nombre', $term)
                  ->groupEnd();
        } elseif (!empty($id)) {
             // Lógica para RECARGA DE VALOR 'OLD' (CLAVE: castear a entero)
             $id_entero = (int)$id;
             if ($id_entero > 0) {
                 $query->where('carne', $id_entero);
             }
        }

        $usuarios = $query->findAll();

        // Formatear resultados
        $results = array_map(function($usuario) {
            $text = "{$usuario['carne']} - {$usuario['nombre']}";
            return ['id' => $usuario['carne'], 'text' => $text]; 
        }, $usuarios);

        return $this->response->setJSON(['results' => $results]);
    }
}