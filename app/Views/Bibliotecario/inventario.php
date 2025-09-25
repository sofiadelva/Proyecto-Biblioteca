<!-- Esta vista hereda/usa la plantilla principal del bibliotecario -->
<?= $this->extend('Plantillas/plantilla_biblio'); ?>

<!-- Sección para el título de la página -->
<?= $this->section('titulo'); ?>
Inventario
<?= $this->endSection(); ?>

<!-- Sección para el contenido principal -->
<?= $this->section('contenido'); ?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<!-- Contenedor para filtros: búsqueda y orden -->
<div class="d-flex justify-content-between align-items-center mb-3">

    <!-- Formulario de búsqueda (filtra por título o autor) -->
    <form method="get" action="<?= base_url('inventario'); ?>" class="d-flex">
        <input 
            type="text" 
            name="buscar" 
            class="form-control me-2" 
            placeholder="Buscar por título o autor..." 
            value="<?= esc($buscar ?? '') ?>"  <!-- Mantiene el texto buscado si ya se envió -->
        >
        <button type="submit" class="btn btn-success" style="background-color:#206060; border:none;">
            Buscar
        </button>
    </form>

    <!-- Formulario de orden (ordena los resultados según el criterio seleccionado) -->
    <form class="d-inline" method="get" action="<?= base_url('inventario'); ?>">
        <select name="ordenar" class="form-select d-inline w-auto">
            <option value="">Ordenar por...</option>
            <option value="titulo_asc">Título A → Z</option>
            <option value="titulo_desc">Título Z → A</option>
            <option value="autor_asc">Autor A → Z</option>
            <option value="autor_desc">Autor Z → A</option>
            <option value="reciente">Más reciente</option>
            <option value="viejo">Más viejo</option>
        </select>
        <button type="submit" class="btn btn-primary">Ordenar</button>
    </form>
</div>

<!-- Tabla donde se listan los libros disponibles del inventario -->
<table class="table table-hover table-bordered my-3">
    <thead class="table-dark">
        <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>Editorial</th>
            <th>Cantidad Disponibles</th>
            <th>Categoría</th>
        </tr>
    </thead>
    <tbody>
        <!-- Si existen libros en la variable $libros -->
        <?php if (!empty($libros)): ?>
            <!-- Recorre cada libro de la lista -->
            <?php foreach($libros as $libro): ?>
                <!-- Solo se muestran los libros cuyo estado sea "Disponible" -->
                <?php if ($libro['estado'] === 'Disponible'): ?>
                    <tr>
                        <td><?= esc($libro['titulo']) ?></td>
                        <td><?= esc($libro['autor']) ?></td>
                        <td><?= esc($libro['editorial']) ?></td>
                        <td><?= esc($libro['cantidad_disponibles']) ?></td>
                        <td><?= esc($libro['categoria']) ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Si no hay libros, se muestra un mensaje -->
            <tr>
                <td colspan="5" class="text-center text-muted">No hay libros disponibles en el inventario.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Fin de la sección de contenido -->
<?= $this->endSection(); ?>
