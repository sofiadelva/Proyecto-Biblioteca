<?= $this->extend('Plantillas/plantilla_admin'); ?>

<?= $this->section('titulo'); ?>
Ejemplares de <?= esc($libro['titulo']) ?>
<?= $this->endSection(); ?>

<?= $this->section('contenido'); ?>

<div class="mb-3">
    <a href="<?= base_url('ejemplares/new/'.$libro['libro_id']) ?>" class="btn btn-success">Agregar Ejemplar</a>
    <a href="<?= base_url('libros') ?>" class="btn btn-secondary">Volver a Libros</a>
</div>

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
            <?php $i = 1; ?>
            <?php foreach ($ejemplares as $ej): ?>
            <tr>
                <td><?= $i++ ?></td> <!-- número de copia -->
                <td><?= esc($ej['titulo_libro']) ?></td>
                <td><?= esc($ej['estado']) ?></td>
                <td>
                    <a href="<?= base_url('ejemplares/edit/'.$ej['ejemplar_id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="<?= base_url('ejemplares/delete/'.$ej['ejemplar_id']) ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Seguro que quieres eliminar este ejemplar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">No hay ejemplares registrados para este libro</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection(); ?>
