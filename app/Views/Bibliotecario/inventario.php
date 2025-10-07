<?php 
// Extiende de la plantilla principal del bibliotecario
echo $this->extend('Plantillas/plantilla_biblio'); 
?>

<?php 
// Dejamos el título en blanco o corto para que no estorbe en la barra superior de la plantilla
$this->section('titulo'); 
?>
Inventario
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido"
$this->section('contenido'); 
?>

<style>
    :root {
        --color-primary: #206060; /* Verde Oscuro */
        --color-accent: #0f7a7a; /* Tono de hover/secundario */
    }

    /* ESTILO DEL ENCABEZADO DENTRO DEL CONTENIDO */
    .page-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #343a40; /* Color de texto oscuro */
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e0e0e0; /* Línea separadora sutil */
    }

    /* Barra de Búsqueda (Copiado de la plantilla para consistencia) */
    .search-bar-container {
        display: flex;
        align-items: center;
        background-color: var(--color-primary); 
        border-radius: 8px;
        padding: 2px;
        width: 400px; 
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .search-bar-container input[type="text"] {
        flex-grow: 1;
        padding: 10px 15px;
        border: none;
        border-radius: 8px 0 0 8px; 
        font-size: 1rem;
        outline: none;
        color: #343a40; 
    }
    .search-bar-container .search-icon {
        background-color: var(--color-accent); 
        color: #ffffff;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 0 8px 8px 0; 
        font-size: 1.2rem;
        transition: background-color 0.3s ease;
        height: 42px; 
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .search-bar-container .search-icon:hover {
        background-color: #074747;
    }
    
    /* Indicadores de estado (Colores corregidos) */
    .badge-estado-disponible {
        background-color: #61A392 !important; /* Verde claro/cielo */
        color: white;
    }
    .badge-estado-danado {
        background-color: #DC3545 !important; /* Rojo oscuro/ladrillo */
        color: white;
    }
    .badge-estado-prestado {
        background-color: #FFC107 !important; /* Amarillo/Naranja */
        color: #343a40; /* Texto oscuro para contraste */
    }

</style>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<h1 class="page-title mb-4">Inventario de Libros</h1>

<div class="d-flex justify-content-end align-items-center mb-4">
    <form method="get" action="<?= base_url('inventario'); ?>" class="search-bar-container">
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar por Título o Autor..." 
            value="<?= esc($buscar ?? '') ?>" 
        />
        <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
        <input type="hidden" name="estado" value="<?= esc($_GET['estado'] ?? '') ?>">
        <input type="hidden" name="cantidad_disponible" value="<?= esc($_GET['cantidad_disponible'] ?? '') ?>">

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
                
                <form class="d-flex align-items-center" method="get" action="<?= base_url('inventario'); ?>">
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
                <form class="d-flex align-items-center" method="get" action="<?= base_url('inventario'); ?>">
                    <select name="estado" class="form-select w-auto me-2">
                        <option value="">Estado...</option>
                        <option value="Disponible" <?= (isset($_GET['estado']) && $_GET['estado'] == 'Disponible') ? 'selected' : '' ?>>Disponible</option>
                        <option value="Dañado" <?= (isset($_GET['estado']) && $_GET['estado'] == 'Dañado') ? 'selected' : '' ?>>Dañado</option>
                        <option value="Prestado" <?= (isset($_GET['estado']) && $_GET['estado'] == 'Prestado') ? 'selected' : '' ?>>Prestado</option>
                    </select>
                    <select name="cantidad_disponible" class="form-select w-auto me-2">
                        <option value="">Cantidad...</option>
                        <option value="0" <?= (isset($_GET['cantidad_disponible']) && $_GET['cantidad_disponible'] == '0') ? 'selected' : '' ?>>0 disponibles</option>
                        <option value="1" <?= (isset($_GET['cantidad_disponible']) && $_GET['cantidad_disponible'] == '1') ? 'selected' : '' ?>>1 o más</option>
                    </select>
                    <button type="submit" class="btn btn-secondary" style="background-color: var(--color-primary); color:white; border:none;"><i class="bi bi-search"></i> Aplicar Filtro</button>
                    
                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                    <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
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
            <th>Cantidad Total</th>
            <th>Cantidad Disponibles</th>
            <th>Estado</th>
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
                    <td><?= esc($libro['cantidad_total'] ?? 'N/A') ?></td>
                    <td><?= esc($libro['cantidad_disponibles'] ?? 0) ?></td>
                    <td>
                        <span class="badge 
                            <?php 
                            if ($libro['estado'] === 'Disponible') echo 'badge-estado-disponible'; 
                            else if ($libro['estado'] === 'Dañado') echo 'badge-estado-danado'; 
                            else if ($libro['estado'] === 'Prestado') echo 'badge-estado-prestado'; 
                            else echo 'bg-secondary';
                            ?>">
                            <?= esc($libro['estado']) ?>
                        </span>
                    </td>
                    <td><?= esc($libro['categoria']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center text-muted">No se encontraron libros que coincidan con los criterios.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if (isset($pager)): ?>
<div class="mt-4">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>
<?php endif; ?>

<?php 
$this->endSection(); 
?>