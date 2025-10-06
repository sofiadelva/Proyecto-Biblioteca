<?php 
// Extiende de la plantilla principal llamada "plantilla_admin"
echo $this->extend('Plantillas/plantilla_admin'); 
?>

<?php 
// Define la sección "titulo" de la plantilla
$this->section('titulo'); 
?>
Categorías
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido" que se mostrará en el layout
$this->section('contenido'); 
?>

<?php 
if(session()->getFlashdata('msg')): 
?>
    <div class="alert alert-success"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="<?= base_url('categorias/create'); ?>" class="btn btn-lg text-white shadow" style="background-color:#206060;">
        <i class="bi bi-plus-circle-fill me-2"></i>Agregar Nueva Categoría
    </a>
    
    <form method="get" action="<?= base_url('categorias'); ?>" class="search-bar-container">
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar por Nombre de Categoría..." 
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
                
                <form class="d-flex align-items-center mb-3" method="get" action="<?= base_url('categorias'); ?>">
                    <input type="number" name="per_page" value="<?= $perPage ?? 10 ?>" min="1" class="form-control w-auto me-2" style="max-width: 150px;" placeholder="Filas">
                    
                    <select name="ordenar" class="form-select w-auto me-2">
                        <option value="">Ordenar por...</option>
                        <option value="nombre_asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'nombre_asc') ? 'selected' : '' ?>>Nombre A → Z</option>
                        <option value="nombre_desc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'nombre_desc') ? 'selected' : '' ?>>Nombre Z → A</option>
                        <option value="reciente" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'reciente') ? 'selected' : '' ?>>Más reciente</option>
                        <option value="viejo" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'viejo') ? 'selected' : '' ?>>Más viejo</option>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-right-short"></i> Aplicar</button>

                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3"></div>
</div>

<table class="table clean-table my-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if (!empty($categorias)):
            foreach($categorias as $categoria): 
        ?>
        <tr>
            <td><?= $categoria['categoria_id'] ?></td>
            <td><?= $categoria['nombre'] ?></td>
            <td>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('categorias/edit/'.$categoria['categoria_id']); ?>" 
                       class="btn-sm btn-accion-editar">Editar</a>

                    <a href="<?= base_url('categorias/delete/'.$categoria['categoria_id']); ?>" 
                       class="btn-sm btn-accion-eliminar"
                       onclick="return confirm('¿Seguro que quieres eliminar esta categoría?')">Eliminar</a>
                </div>
            </td>
        </tr>
        <?php 
            endforeach; 
        else:
        ?>
        <tr>
            <td colspan="3" class="text-center text-muted">No se encontraron categorías.</td>
        </tr>
        <?php 
        endif; 
        ?>
    </tbody>
</table>

<div class="mt-4">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>

<?php 
$this->endSection(); 
?>