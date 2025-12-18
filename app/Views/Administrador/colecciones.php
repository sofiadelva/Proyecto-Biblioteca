<?php 
// Extiende de la plantilla principal llamada "plantilla_admin"
echo $this->extend('Plantillas/plantilla_admin'); 
?>

<?php 
// Define la sección "titulo" de la plantilla
$this->section('titulo'); 
?>
Colecciones de Biblioteca
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido" que se mostrará en el layout
$this->section('contenido'); 
?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="<?= base_url('colecciones/create'); ?>" class="btn btn-lg text-white shadow" style="background-color:#0C1E44;">
        <i class="bi bi-plus-circle-fill me-2"></i>Agregar Nueva Colección
    </a>
    
    <form method="get" action="<?= base_url('colecciones'); ?>" class="search-bar-container">
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar colección, subgénero..." 
            value="<?= esc($buscar ?? '') ?>" 
        />
        <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
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
                
                <form class="d-flex align-items-center mb-3" method="get" action="<?= base_url('colecciones'); ?>">
                    <input type="number" name="per_page" value="<?= $perPage ?? 10 ?>" min="1" class="form-control w-auto me-2" style="max-width: 100px;" placeholder="Filas">
                    
                    <select name="ordenar" class="form-select w-auto me-2">
                        <option value="">Ordenar por...</option>
                        <option value="nombre_asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'nombre_asc') ? 'selected' : '' ?>>Nombre A → Z</option>
                        <option value="nombre_desc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'nombre_desc') ? 'selected' : '' ?>>Nombre Z → A</option>
                        <option value="reciente" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'reciente') ? 'selected' : '' ?>>Más reciente</option>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-right-short"></i> Aplicar</button>

                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>
</div>

<table class="table clean-table my-3 shadow-sm">
    <thead>
        <tr>
            <th>No.</th>
            <th>Colección Principal</th>
            <th>Subgénero</th>
            <th>Subcategoría</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if (!empty($colecciones)):
            foreach($colecciones as $i => $fila): 
        ?>
        <tr>
            <td><span><?= (isset($pager) && $pager->getCurrentPage() > 1) ? ($pager->getPerPage() * ($pager->getCurrentPage() - 1)) + ($i + 1) : ($i + 1) ?></span></td>
            <td><strong><?= esc($fila['coleccion']) ?></strong></td>
            <td>
                <?= !empty($fila['subgenero']) ? '<span>'.$fila['subgenero'].'</span>' : '<small class="text-muted">Sin subgénero</small>' ?>
            </td>
            <td>
                <?= !empty($fila['subcategoria']) ? '<span class="text-secondary">'.$fila['subcategoria'].'</span>' : '<small class="text-muted">—</small>' ?>
            </td>
            <td>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('colecciones/edit/'.$fila['coleccion_id']); ?>" 
                       class="btn-sm btn-accion-editar text-decoration-none">
                       <i class="bi bi-pencil-square"></i> Editar
                    </a>

                    <a href="<?= base_url('colecciones/delete/'.$fila['coleccion_id']); ?>" 
                       class="btn-sm btn-accion-eliminar text-decoration-none"
                       onclick="return confirm('¿Seguro que quieres eliminar esta colección?')">
                       <i class="bi bi-trash"></i> Eliminar
                    </a>
                </div>
            </td>
        </tr>
        <?php 
            endforeach; 
        else:
        ?>
        <tr>
            <td colspan="5" class="text-center py-4 text-muted">
                <i class="bi bi-info-circle me-2"></i>No se encontraron colecciones registradas.
            </td>
        </tr>
        <?php 
        endif; 
        ?>
    </tbody>
</table>

<div class="mt-4">
    <?php if (isset($pager)): ?>
        <?= $pager->links('default', 'bootstrap_full') ?>
    <?php endif; ?>
</div>

<?php 
$this->endSection(); 
?>