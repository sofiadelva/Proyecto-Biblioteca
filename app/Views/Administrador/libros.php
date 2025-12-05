<?php 
// Extiende de la plantilla principal llamada "plantilla_admin"
echo $this->extend('Plantillas/plantilla_admin'); 
?>

<?php 
// Define la sección "titulo" de la plantilla
$this->section('titulo'); 
?>
Libros
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido" que se mostrará en el layout
$this->section('contenido'); 
?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="<?= base_url('libros/new'); ?>" class="btn btn-lg text-white shadow" style="background-color:#0C1E44;">
        <i class="bi bi-plus-circle-fill me-2"></i>Agregar Nuevo Libro
    </a>
    
    <form method="get" action="<?= base_url('libros'); ?>" class="search-bar-container">
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar por Título o Autor..." 
            value="<?= esc($buscar ?? '') ?>" 
        />
        <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
        <input type="hidden" name="estado" value="<?= esc($_GET['estado'] ?? '') ?>">
        <input type="hidden" name="cantidad_disponible" value="<?= esc($_GET['cantidad_disponible'] ?? '') ?>">
        <input type="hidden" name="per_page" value="<?= esc($_GET['per_page'] ?? '') ?>">

        <button type="submit" class="search-icon">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>
<div class="row mb-3">
    
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-secondary border-opacity-25">
            <div class="card-body py-3">
                <h6 class="card-title text-muted mb-3"><i class="bi bi-sort-alpha-down me-2"></i>Opciones de Visualización</h6>
                
                <form class="d-flex align-items-center mb-3" method="get" action="<?= base_url('libros'); ?>">
                    <input type="number" name="per_page" value="<?= $perPage ?? 10 ?>" min="1" class="form-control w-auto me-2" style="max-width: 150px;" placeholder="Filas">
                    
                    <select name="ordenar" class="form-select w-auto me-2">
                        <option value="">Ordenar por...</option>
                        <option value="titulo_asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'titulo_asc') ? 'selected' : '' ?>>Título A → Z</option>
                        <option value="titulo_desc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'titulo_desc') ? 'selected' : '' ?>>Título Z → A</option>
                        <option value="autor_asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'autor_asc') ? 'selected' : '' ?>>Autor A → Z</option>
                        <option value="autor_desc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'autor_desc') ? 'selected' : '' ?>>Autor Z → A</option>
                        <option value="reciente" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'reciente') ? 'selected' : '' ?>>Más reciente</option>
                        <option value="viejo" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'viejo') ? 'selected' : '' ?>>Más viejo</option>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-right-short"></i> Aplicar</button>

                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                    <input type="hidden" name="estado" value="<?= esc($_GET['estado'] ?? '') ?>">
                    <input type="hidden" name="cantidad_disponible" value="<?= esc($_GET['cantidad_disponible'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-secondary border-opacity-25">
            <div class="card-body py-3">
                <h6 class="card-title text-muted mb-3"><i class="bi bi-funnel-fill me-2"></i>Opciones de Filtrado</h6>
                <form class="d-flex align-items-center" method="get" action="<?= base_url('libros'); ?>">
                    <select name="estado" class="form-select w-auto me-2">
                        <option value="">Estado...</option>
                        <option value="Disponible" <?= (isset($_GET['estado']) && $_GET['estado'] == 'Disponible') ? 'selected' : '' ?>>Disponible</option>
                        <option value="Dañado" <?= (isset($_GET['estado']) && $_GET['estado'] == 'Dañado') ? 'selected' : '' ?>>Dañado</option>
                    </select>
                    <select name="cantidad_disponible" class="form-select w-auto me-2">
                        <option value="">Cantidad...</option>
                        <option value="0" <?= (isset($_GET['cantidad_disponible']) && $_GET['cantidad_disponible'] == '0') ? 'selected' : '' ?>>0 disponibles</option>
                        <option value="1" <?= (isset($_GET['cantidad_disponible']) && $_GET['cantidad_disponible'] == '1') ? 'selected' : '' ?>>1 o más</option>
                    </select>
                    <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Aplicar Filtro</button>
                    
                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                    <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
                    <input type="hidden" name="per_page" value="<?= esc($_GET['per_page'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>
</div>

<table class="table clean-table my-3">
    <thead>
    <tr>
        <th>Código</th> 
        <th>Título</th>
        <th>Autor</th>
        <th>Editorial</th>
        <th>Año</th>
        <th>Páginas</th>
        <th>Colección</th> 
        <th>Subgénero</th>
        <th>Subcategoría</th>
        <th>Cantidad Total</th>
        <th>Cantidad Disponibles</th>
        <th>Estado</th>
        <th>Opciones</th>
    </tr>
    </thead>
    <tbody>
    <?php 
    // Usamos ?? 'N/A' para manejar el caso en que el LEFT JOIN no encuentre un registro
    foreach($libros as $libro): 
    ?>
        <tr>
            <td><?= esc($libro['codigo']) ?></td> 
            <td><?= esc($libro['titulo']) ?></td>
            <td><?= esc($libro['autor']) ?></td>
            <td><?= esc($libro['editorial']) ?></td>
            <td><?= esc($libro['ano']) ?></td> 
            <td><?= esc($libro['paginas']) ?></td> 
            
            <td><?= esc($libro['coleccion_nombre'] ?? 'N/A') ?></td> 
            <td><?= esc($libro['subgenero_nombre'] ?? 'N/A') ?></td> 
            <td><?= esc($libro['subcategoria_nombre'] ?? '') ?></td> 
            
            <td><?= esc($libro['cantidad_total']) ?></td>
            <td><?= esc($libro['cantidad_disponibles']) ?></td>
            <td><?= esc($libro['estado']) ?></td>
            <td>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('libros/edit/'.$libro['libro_id']); ?>" 
                        class="btn-sm btn-accion-editar">Editar</a>

                    <a href="<?= base_url('libros/delete/'.$libro['libro_id']); ?>" 
                        class="btn-sm btn-accion-eliminar"
                        onclick="return confirm('¿Seguro que quieres eliminar este libro?')">Eliminar</a>

                    <a href="<?= base_url('ejemplares/listar/'.$libro['libro_id']); ?>" 
                        class="btn btn-sm btn-info"
                        title="Ver Ejemplares"
                        style="background-color:#206060; color:#fff; border-color:#206060;">
                        <i class="bi bi-list-columns-reverse"></i>
                    </a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="mt-4">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>

<?php 
$this->endSection(); 
?>