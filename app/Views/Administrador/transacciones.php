<?php echo $this->extend('Plantillas/plantilla_admin'); ?>

<?php $this->section('titulo'); ?>
Transacciones
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<div class="mb-3">
    <a href="<?= base_url('transacciones/create'); ?>" class="btn btn-success">Agregar Transacción</a>
</div>

<table class="table table-hover table-bordered my-3">
    <thead class="table-dark">
        <tr>
            <th>Título del Libro</th>
            <th>Número de Ejemplar</th>
            <th>Usuario</th>
            <th>Fecha Préstamo</th>
            <th>Fecha Devolución</th>
            <th>Fecha Devuelto</th>
            <th>Estado</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($transacciones as $t): ?>
        <tr>
            <td><?= $t['titulo'] ?></td>
            <td><?= $t['no_copia'] ?></td>
            <td><?= $t['usuario_nombre'] ?></td>
            <td><?= $t['fecha_prestamo'] ?></td>
            <td><?= $t['fecha_de_devolucion'] ?></td>
            <td><?= $t['fecha_devuelto'] ?></td>
            <td><?= $t['estado'] ?></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="<?= base_url('transacciones/edit/'.$t['prestamo_id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="<?= base_url('transacciones/delete/'.$t['prestamo_id']); ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Seguro que quieres eliminar esta transacción?')">Eliminar</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $this->endSection(); ?>
