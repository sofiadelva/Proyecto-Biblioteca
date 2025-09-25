<?php echo $this->extend('Plantillas/plantilla_admin'); ?> 
<!-- Extiende la plantilla principal llamada "plantilla_admin". -->

<?php $this->section('titulo'); ?>
Transacciones
<?php $this->endSection(); ?>
<!-- Sección para definir el título de la página, en este caso "Transacciones" -->

<?php $this->section('contenido'); ?>
<!-- Sección principal de contenido de la vista -->

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<div class="mb-3">
    <a href="<?= base_url('transacciones/create'); ?>" class="btn btn-success">Agregar Transacción</a>
</div>

<!-- Tabla para mostrar las transacciones registradas -->
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
        <!-- Ciclo que recorre todas las transacciones enviadas desde el controlador -->
        <?php foreach($transacciones as $t): ?>
        <tr>
            <!-- Se muestran los datos de cada transacción -->
            <td><?= $t['titulo'] ?></td>
            <td><?= $t['no_copia'] ?></td>
            <td><?= $t['usuario_nombre'] ?></td>
            <td><?= $t['fecha_prestamo'] ?></td>
            <td><?= $t['fecha_de_devolucion'] ?></td>
            <td><?= $t['fecha_devuelto'] ?></td>
            <td><?= $t['estado'] ?></td>
            <td>
                <div class="d-flex gap-1">
                    <!-- Botón para editar la transacción -->
                    <a href="<?= base_url('transacciones/edit/'.$t['prestamo_id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    
                    <!-- Botón para eliminar la transacción con confirmación -->
                    <a href="<?= base_url('transacciones/delete/'.$t['prestamo_id']); ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Seguro que quieres eliminar esta transacción?')">Eliminar</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $this->endSection(); ?>
<!-- Cierre de la sección de contenido -->
