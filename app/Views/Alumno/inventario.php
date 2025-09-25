<?= $this->extend('Plantillas/plantilla_alumno'); ?>  
<!-- Extiende la plantilla específica para alumnos. -->

<?= $this->section('titulo'); ?>
Inventario
<?= $this->endSection(); ?>  
<!-- Título de la página. -->

<?= $this->section('contenido'); ?>  
<!-- Contenido principal. -->

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>  
<!-- Muestra mensajes flash (notificaciones). -->

<!-- Filtros: búsqueda y orden -->
<div class="d-flex justify-content-between align-items-center mb-3">

    <!-- Barra de búsqueda -->
    <form method="get" action="<?= base_url('inventario_alumno'); ?>" class="d-flex">
        <input 
            type="text" 
            name="buscar" 
            class="form-control me-2" 
            placeholder="Buscar por título o autor..." 
            value="<?= esc($buscar ?? '') ?>"
        >
        <button type="submit" class="btn btn-success" style="background-color:#206060; border:none;">
            Buscar
        </button>
    </form>
    <!-- Permite buscar libros por título o autor. -->

    <!-- Ordenar -->
    <form class="d-inline" method="get" action="<?= base_url('inventario_alumno'); ?>">
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
    <!-- Permite ordenar la tabla según diferentes criterios. -->
</div>

<!-- Tabla de inventario -->
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
        <?php if (!empty($libros)): ?>
            <?php foreach($libros as $libro): ?>
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
            <tr>
                <td colspan="5" class="text-center text-muted">No hay libros disponibles en el inventario.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<!-- Muestra los libros disponibles filtrando solo los que están en estado 'Disponible'. -->

<?= $this->endSection(); ?>  
<!-- Fin de la sección de contenido. -->
