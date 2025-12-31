<?php
namespace App\Controllers;

use App\Models\ColeccionModel;
use App\Models\SubgeneroModel;
use App\Models\SubcategoriaModel;
use CodeIgniter\Controller;

class Colecciones extends Controller
{
    protected $coleccionModel;

    public function __construct()
    {
        $this->coleccionModel = new ColeccionModel();
        helper(['form', 'url']);
    }

    /**
     * Lista las colecciones con su jerarquía (Subgénero y Subcategoría)
     * Incluye Paginación, Búsqueda y Ordenamiento
     */
    public function index()
{
    $buscar = $this->request->getGet('buscar');

    // 1. Obtenemos los datos planos con Joins
    $builder = $this->coleccionModel
        ->select('colecciones.coleccion_id, colecciones.nombre as coleccion, 
                  subgeneros.subgenero_id, subgeneros.nombre as subgenero, 
                  subcategorias.subcategoria_id, subcategorias.nombre as subcategoria')
        ->join('subgeneros', 'subgeneros.coleccion_id = colecciones.coleccion_id', 'left')
        ->join('subcategorias', 'subcategorias.subgenero_id = subgeneros.subgenero_id', 'left');

    if ($buscar) {
        $builder->groupStart()
                ->like('colecciones.nombre', $buscar)
                ->orLike('subgeneros.nombre', $buscar)
                ->orLike('subcategorias.nombre', $buscar)
                ->groupEnd();
    }

    $resultados = $builder->findAll();

    // 2. Agrupamos jerárquicamente en un array multidimensional
    $jerarquia = [];
    foreach ($resultados as $row) {
        $cID = $row['coleccion_id'];
        $sGID = $row['subgenero_id'];
        $sCID = $row['subcategoria_id'];

        if (!isset($jerarquia[$cID])) {
            $jerarquia[$cID] = [
                'id' => $cID,
                'nombre' => $row['coleccion'],
                'subgeneros' => []
            ];
        }

        if ($sGID) {
            if (!isset($jerarquia[$cID]['subgeneros'][$sGID])) {
                $jerarquia[$cID]['subgeneros'][$sGID] = [
                    'id' => $sGID,
                    'nombre' => $row['subgenero'],
                    'subcategorias' => []
                ];
            }

            // Solo agregamos la subcategoría si existe nombre y no es un "comodín" vacío
            if ($sCID && !empty($row['subcategoria']) && strtoupper($row['subcategoria']) !== 'NULL') {
                $jerarquia[$cID]['subgeneros'][$sGID]['subcategorias'][$sCID] = [
                    'id' => $sCID,
                    'nombre' => $row['subcategoria']
                ];
            }
        }
    }

    return view('Administrador/colecciones', [
        'jerarquia' => $jerarquia,
        'buscar' => $buscar
    ]);
}

    /**
     * Formulario para nueva colección
     */
    public function create()
    {
        return view('Administrador/Colecciones/nuevo');
    }

    /**
     * Guardar nueva colección
     */
    public function store()
{
    $nombreColeccion = $this->request->getPost('nombre');
    $subPost = $this->request->getPost('subgeneros');

    // 1. Limpieza inicial
    $nombreColTrim = trim($nombreColeccion);
    $subgeneros = is_array($subPost) ? array_filter($subPost, fn($v) => !empty(trim($v))) : [];

    // 2. VALIDACIÓN: ¿Existe ya la colección? (Case insensitive)
    $existeCol = $this->coleccionModel->where("LOWER(nombre)", strtolower($nombreColTrim))->first();
    if ($existeCol) {
        return redirect()->back()->withInput()->with('errors', ["La colección '$nombreColTrim' ya existe. Intente agregar subgéneros a la existente."]);
    }

    if (empty($nombreColTrim) || empty($subgeneros)) {
        return redirect()->back()->withInput()->with('errors', ['Debe escribir el nombre de la colección y al menos un subgénero.']);
    }

    $db = \Config\Database::connect();
    $db->transStart();

    // 3. Insertar Colección
    $this->coleccionModel->insert(['nombre' => $nombreColTrim]);
    $coleccionId = $this->coleccionModel->getInsertID();

    $subgeneroModel = new \App\Models\SubgeneroModel();
    $subcategoriaModel = new \App\Models\SubcategoriaModel();
    $subgenerosProcesados = [];

    foreach ($subgeneros as $nombreSG) {
        $sgTrim = trim($nombreSG);
        $sgLower = strtolower($sgTrim);

        // Evitar duplicados dentro del mismo formulario (si el usuario escribió lo mismo dos veces)
        if (in_array($sgLower, $subgenerosProcesados)) continue;

        $idSG = $subgeneroModel->insert([
            'nombre' => $sgTrim,
            'coleccion_id' => $coleccionId
        ]);

        $subcategoriaModel->insert([
            'nombre' => null,
            'subgenero_id' => $idSG
        ]);

        $subgenerosProcesados[] = $sgLower;
    }

    $db->transComplete();

    if ($db->transStatus() === FALSE) {
        return redirect()->back()->withInput()->with('errors', ['Error al procesar la base de datos.']);
    }

    return redirect()->to(base_url('colecciones'))->with('msg', 'Colección y subgéneros creados con éxito.');
}
    /**
     * Formulario de edición
     */
   // Cargar la vista de edición con toda la jerarquía de esa colección
public function edit($id) {
    $data['coleccion'] = $this->coleccionModel->find($id);
    
    // Traer subgéneros y subcategorías de esta colección
    // (Aquí usas una lógica similar a la del index pero filtrada por ID)
    $data['jerarquia'] = $this->getJerarquiaByColeccion($id); 
    
    return view('Administrador/Colecciones/edit', $data);
}

// Actualizar el nombre de la colección principal
public function update($id) {
    $nombre = $this->request->getPost('nombre');
    
    // Validación de duplicado
    $existe = $this->coleccionModel->where("LOWER(nombre)", strtolower(trim($nombre)))
                                   ->where("coleccion_id !=", $id)->first();
    if ($existe) return redirect()->back()->with('errors', ['Ese nombre ya existe.']);

    $this->coleccionModel->update($id, ['nombre' => trim($nombre)]);
    return redirect()->back()->with('msg', 'Nombre actualizado.');
}

// Actualizar un subgénero (se llama desde el modal)
public function update_subgen($id) {
    $nombre = $this->request->getPost('nombre_sub');
    $subgeneroModel = new \App\Models\SubgeneroModel();
    
    $subgeneroModel->update($id, ['nombre' => trim($nombre)]);
    return redirect()->back()->with('msg', 'Subgénero actualizado.');
}

public function update_subcat($id) {
    $subcategoriaModel = new \App\Models\SubcategoriaModel();
    $nuevoNombre = trim($this->request->getPost('nombre_sc'));

    // 1. Obtener los datos actuales para saber a qué subgénero pertenece
    $actual = $subcategoriaModel->find($id);
    
    // 2. Validar que no exista otra subcategoría igual en el mismo subgénero
    $existe = $subcategoriaModel->where('subgenero_id', $actual['subgenero_id'])
                                ->where('LOWER(nombre)', strtolower($nuevoNombre))
                                ->where('subcategoria_id !=', $id)
                                ->first();

    if ($existe) {
        return redirect()->back()->with('errors', ["La subcategoría '$nuevoNombre' ya existe en este subgénero."]);
    }

    // 3. Actualizar
    $subcategoriaModel->update($id, ['nombre' => $nuevoNombre]);
    return redirect()->back()->with('msg', 'Subcategoría actualizada correctamente.');
}
    public function delete($id)
    {
        // Nota: Debido a tus restricciones SQL (ON DELETE CASCADE), 
        // eliminar una colección eliminará automáticamente sus subgéneros y subcategorías.
        $this->coleccionModel->delete($id);
        
        return redirect()->to(base_url('colecciones'))->with('msg', 'Colección eliminada correctamente');
    }

    public function nuevoSubgenero()
{
    $data['todas_colecciones'] = $this->coleccionModel->orderBy('nombre', 'ASC')->findAll();
    return view('Administrador/Colecciones/nuevo_subgenero', $data);
}

public function guardarSubgenero()
{
    $coleccionId = $this->request->getPost('coleccion_id');
    $subPost = $this->request->getPost('subgeneros');

    // 1. Limpiar y filtrar entradas vacías
    $subgeneros = is_array($subPost) ? array_filter($subPost, fn($v) => !empty(trim($v))) : [];

    if (!$coleccionId || empty($subgeneros)) {
        return redirect()->back()->withInput()->with('errors', ['Seleccione una colección y escriba al menos un subgénero.']);
    }

    $subgeneroModel = new \App\Models\SubgeneroModel();
    $subcategoriaModel = new \App\Models\SubcategoriaModel();
    $erroresDuplicados = [];
    $creadosContador = 0;

    $db = \Config\Database::connect();
    $db->transStart();

    foreach ($subgeneros as $nombre) {
        $nombreTrim = trim($nombre);

        // 2. VALIDACIÓN ESTRICTA: Ignora mayúsculas/minúsculas
        $existe = $subgeneroModel->where('coleccion_id', $coleccionId)
                                 ->where("LOWER(nombre)", strtolower($nombreTrim))
                                 ->first();

        if ($existe) {
            $erroresDuplicados[] = "El subgénero '$nombreTrim' ya existe en esta colección.";
            continue; 
        }

        // 3. Guardar si no existe
        $idSG = $subgeneroModel->insert([
            'nombre' => $nombreTrim,
            'coleccion_id' => $coleccionId
        ]);

        $subcategoriaModel->insert([
            'nombre' => null,
            'subgenero_id' => $idSG
        ]);
        
        $creadosContador++;
    }

    $db->transComplete();

    // 4. Manejo de respuestas según lo que pasó
    if (!empty($erroresDuplicados)) {
        // Si hubo errores, los mandamos de vuelta por Flashdata
        session()->setFlashdata('errors', $erroresDuplicados);
        
        if ($creadosContador > 0) {
            return redirect()->to(base_url('colecciones'))->with('msg', "Se agregaron $creadosContador subgéneros, pero algunos ya existían.");
        } else {
            return redirect()->back()->withInput();
        }
    }

    return redirect()->to(base_url('colecciones'))->with('msg', 'Subgéneros agregados exitosamente.');
}

    // Carga la vista
    public function nuevaSubcategoria() {
        $data['todas_colecciones'] = $this->coleccionModel->orderBy('nombre', 'ASC')->findAll();
        return view('Administrador/Colecciones/nueva_subcategoria', $data);
    }

    // Responde al AJAX de la vista
    public function getSubgeneros($coleccionId) {
        $subgeneroModel = new \App\Models\SubgeneroModel();
        $data = $subgeneroModel->where('coleccion_id', $coleccionId)->orderBy('nombre', 'ASC')->findAll();
        return $this->response->setJSON($data);
    }

    // Guarda los datos
    public function guardarSubcategoria() {
    $subgeneroId = $this->request->getPost('subgenero_id');
    $subcatPost = $this->request->getPost('subcategorias');

    // 1. Limpieza y filtrado de nulos/vacíos
    $subcategorias = is_array($subcatPost) ? array_filter($subcatPost, fn($v) => !empty(trim($v))) : [];

    if (!$subgeneroId || empty($subcategorias)) {
        return redirect()->back()->withInput()->with('errors', ['Complete los campos obligatorios.']);
    }

    $subcategoriaModel = new \App\Models\SubcategoriaModel();
    $errores = [];

    // 2. VALIDACIÓN PREVENTIVA: Revisar duplicados antes de insertar cualquier cosa
    foreach ($subcategorias as $nombre) {
        $nombreTrim = trim($nombre);
        $existe = $subcategoriaModel->where('subgenero_id', $subgeneroId)
                                    ->where("LOWER(nombre)", strtolower($nombreTrim))
                                    ->first();
        if ($existe) {
            $errores[] = "La subcategoría '$nombreTrim' ya existe en este subgénero. No se guardó nada.";
        }
    }

    // Si hay errores, detenemos todo y regresamos a la vista anterior
    if (!empty($errores)) {
        return redirect()->back()->withInput()->with('errors', $errores);
    }

    // 3. Si llegamos aquí, es porque todo está limpio. Procedemos a guardar.
    $db = \Config\Database::connect();
    $db->transStart();

    foreach ($subcategorias as $nombre) {
        $subcategoriaModel->insert([
            'nombre' => trim($nombre),
            'subgenero_id' => $subgeneroId
        ]);
    }

    $db->transComplete();

    return redirect()->to(base_url('colecciones'))->with('msg', 'Todas las subcategorías se agregaron con éxito.');
}

/**
 * Función auxiliar para obtener la estructura completa de UNA sola colección
 */
private function getJerarquiaByColeccion($id)
{
    $subgeneroModel = new \App\Models\SubgeneroModel();
    
    // Obtenemos los subgéneros y sus subcategorías mediante un Join
    $resultados = $subgeneroModel
        ->select('subgeneros.subgenero_id, subgeneros.nombre as subgenero, 
                  subcategorias.subcategoria_id, subcategorias.nombre as subcategoria')
        ->join('subcategorias', 'subcategorias.subgenero_id = subgeneros.subgenero_id', 'left')
        ->where('subgeneros.coleccion_id', $id)
        ->findAll();

    $jerarquia = ['subgeneros' => []];

    foreach ($resultados as $row) {
        $sGID = $row['subgenero_id'];
        $sCID = $row['subcategoria_id'];

        if (!isset($jerarquia['subgeneros'][$sGID])) {
            $jerarquia['subgeneros'][$sGID] = [
                'nombre' => $row['subgenero'],
                'subcategorias' => []
            ];
        }

        // Solo agregamos la subcategoría si tiene nombre (no es el comodín NULL)
        if ($sCID && !empty($row['subcategoria']) && strtoupper($row['subcategoria']) !== 'NULL') {
            $jerarquia['subgeneros'][$sGID]['subcategorias'][] = [
                'id'     => $sCID,
                'nombre' => $row['subcategoria']
            ];
        }
    }

    return $jerarquia;
}
    // Eliminar un subgénero individual desde el Edit
    public function delete_subgen($id) {
    $subgeneroModel = new \App\Models\SubgeneroModel();
    
    // 1. Buscamos el subgénero para saber a qué colección pertenece
    $subgenero = $subgeneroModel->find($id);
    
    if (!$subgenero) {
        return redirect()->back()->with('errors', ['El subgénero no existe.']);
    }

    $coleccionId = $subgenero['coleccion_id'];

    // 2. Contamos cuántos subgéneros tiene la colección
    $totalSubgeneros = $subgeneroModel->where('coleccion_id', $coleccionId)->countAllResults();

    // 3. LA REGLA: Si es el último, bloqueamos el borrado
    if ($totalSubgeneros <= 1) {
        return redirect()->back()->with('errors', [
            'Acción denegada: Una colección debe tener al menos un subgénero.',
            'Si ya no necesitas este subgénero, puedes editar su nombre o eliminar la colección completa.'
        ]);
    }

    // 4. Si tiene más de uno, procedemos a borrar sin preguntar por libros
    $subgeneroModel->delete($id);
    
    return redirect()->back()->with('msg', 'Subgénero eliminado correctamente.');
}

    // Eliminar una subcategoría individual desde el Edit
    public function delete_subcat($id) {
        $subcategoriaModel = new \App\Models\SubcategoriaModel();
        $subcategoriaModel->delete($id);
        return redirect()->back()->with('msg', 'Subcategoría eliminada.');
    }
}