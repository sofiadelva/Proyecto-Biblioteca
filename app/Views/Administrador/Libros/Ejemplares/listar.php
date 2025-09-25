<?= $this->extend('Plantillas/plantilla_admin'); ?> 
<!-- Usa la plantilla base "plantilla_admin". -->

<?= $this->section('titulo'); ?>
Ejemplares de <?= esc($libro['titulo']) ?>
<?= $this->endSection(); ?> 
<!-- Sección de título. -->

<?= $this->section('contenido'); ?> 
<!-- Contenido principal de la página. -->

<div class="mb-3">
    <a href="<?= base_url('ejemplares/new/'.$libro['libro_id']) ?>" class="btn btn-success">Agregar Ejemplar</a>
    <a href="<?= base_url('libros') ?>" class="btn btn-secondary">Volver a Libros</a>
</div>
<!-- Botones para agregar un ejemplar nuevo o regresar a la lista de libros. -->

<table class="table table-hover table-bordered my-3">
    <thead class="table-dark">
        <tr>
            <th># Copia</th>
            <th>Título del Libro</th>
            <th>Estado</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($ejemplares): ?> 
            <!-- Si hay ejemplares registrados... -->
            <?php $i = 1; ?> 
            <!-- Contador de copias. -->
            <?php foreach ($ejemplares as $ej): ?>
            <tr>
                <td><?= $i++ ?></td> <!-- Número de copia -->
                <td><?= esc($ej['titulo_libro']) ?></td> <!-- Título del libro -->
                <td><?= esc($ej['estado']) ?></td> <!-- Estado actual -->
                <td>
                    <!-- Botones de acción -->
                    <a href="<?= base_url('ejemplares/edit/'.$ej['ejemplar_id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="<?= base_url('ejemplares/delete/'.$ej['ejemplar_id']) ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Seguro que quieres eliminar este ejemplar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Si no hay ejemplares, muestra mensaje. -->
            <tr>
                <td colspan="4" class="text-center">No hay ejemplares registrados para este libro</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection(); ?> 
<!-- Cierra la sección de contenido. -->
