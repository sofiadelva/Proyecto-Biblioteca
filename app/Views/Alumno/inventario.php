<?php 
// Extiende de la plantilla principal del alumno
echo $this->extend('Plantillas/plantilla_alumno'); 
?>

<?php 
// Define la sección "titulo" de la plantilla
$this->section('titulo'); 
?>
Inventario
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido" que se mostrará en el layout
$this->section('contenido'); 
?>

<style>
    /* Estilos específicos de la tabla/filtros si son necesarios */
    .badge-estado-disponible {
        background-color: #61A392 !important; 
        color: white;
    }
    /* Estilo de la barra de búsqueda copiado */
    .search-bar-container {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 20px;
        padding: 5px 15px;
        background-color: #f8f9fa;
        max-width: 400px;
        transition: all 0.3s;
    }

    .search-bar-container:focus-within {
        border-color: var(--color-primary); 
        box-shadow: 0 0 5px rgba(32, 96, 96, 0.5); 
    }

    .search-bar-container input {
        border: none;
        outline: none;
        background: transparent;
        flex-grow: 1;
        padding: 5px 0;
        font-size: 1rem;
    }

    .search-icon {
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding-left: 10px;
    }
    
</style>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>


<div class="d-flex justify-content-end align-items-center mb-4">
    
    <form method="get" action="<?= base_url('alumno/inventario'); ?>" class="search-bar-container">
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar por Título o Autor..." 
            value="<?= esc($buscar ?? '') ?>" 
        />
        <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
        <input type="hidden" name="categoria_id" value="<?= esc($_GET['categoria_id'] ?? '') ?>">
        <input type="hidden" name="per_page" value="<?= esc($perPage ?? '10') ?>">
        
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
                
                <form class="d-flex align-items-center" method="get" action="<?= base_url('alumno/inventario'); ?>">
                    <input 
                        type="number" 
                        name="per_page" 
                        value="<?= $perPage ?? 10 ?>" 
                        min="1" 
                        max="100"
                        class="form-control w-auto me-2" 
                        style="max-width: 120px;" 
                        placeholder="Filas"
                    >

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
                    <input type="hidden" name="categoria_id" value="<?= esc($_GET['categoria_id'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-secondary border-opacity-25">
            <div class="card-body py-3">
                <h6 class="card-title text-muted mb-3"><i class="bi bi-funnel-fill me-2"></i>Opciones de Filtrado</h6>
                <form class="d-flex align-items-center" method="get" action="<?= base_url('alumno/inventario'); ?>">
                    
                    <select name="categoria_id" class="form-select w-auto me-2">
                        <option value="">Todas las Categorías</option>
                        <?php foreach($categorias as $cat): ?>
                            <option value="<?= esc($cat['categoria_id']) ?>" 
                                <?= (isset($categoriaId) && $categoriaId == $cat['categoria_id']) ? 'selected' : '' ?>>
                                <?= esc($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn-secondary" style="background-color: var(--color-primary); color:white; border:none;"><i class="bi bi-search"></i> Aplicar Filtro</button>
                    
                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                    <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
                    <input type="hidden" name="per_page" value="<?= esc($perPage ?? '10') ?>"> 
                </form>
            </div>
        </div>
    </div>
</div>

<table class="table clean-table my-3">
    <thead>
    <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Editorial</th>
        <th>Cantidad Disponibles</th>
        <th>Categoría</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($libros)): ?>
        <?php foreach($libros as $libro): ?>
            <tr>
                <td><?= esc($libro['titulo']) ?></td>
                <td><?= esc($libro['autor']) ?></td>
                <td><?= esc($libro['editorial']) ?></td>
                <td><?= esc($libro['cantidad_disponibles'] ?? 0) ?></td> 
                <td><?= esc($libro['categoria']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center text-muted">No se encontraron libros disponibles que coincidan con los criterios.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<div class="mt-4">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>

<?php 
$this->endSection(); 
?>