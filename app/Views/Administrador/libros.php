<?php echo $this->extend('plantilla'); ?>
<?php $this->section('contenido'); ?>

<h3 class="my-3" id="titulo">Libros</h3>

<a href="<?= base_url('libros/new'); ?>" class="btn btn-success">Agregar</a>

<table class="table table-hover table-bordered my-3" aria-describedby="titulo">
    <thead class="table-dark">
        <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>Editorial</th>
            <th>Cantidad Total</th>
            <th>Cantidad Disponibles</th>
            <th>Estado</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($libros)): ?>
            <?php foreach($libros as $libro): ?>
                <tr>
                    <td><?= $libro['titulo'] ?></td>
                    <td><?= $libro['autor'] ?></td>
                    <td><?= $libro['editorial'] ?></td>
                    <td><?= $libro['cantidad_total'] ?></td>
                    <td><?= $libro['cantidad_disponibles'] ?></td>
                    <td><?= $libro['estado'] ?></td>
                    <td>
                        <a href="<?= base_url('libros/edit/'.$libro['libro_id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="<?= base_url('libros/delete/'.$libro['libro_id']); ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('¿Seguro que quieres eliminar este libro?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-center">No hay libros registrados</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $this->endSection(); ?>
