<?php echo $this->extend('Plantillas/plantilla_admin'); ?>

<?php $this->section('titulo'); ?>
Categorías
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>

<div class="mb-3">
    <a href="<?= base_url('categorias/create'); ?>" class="btn btn-success">Agregar</a>
</div>

<table class="table table-hover table-bordered my-3">
    <thead class="table-dark">
        <tr>
            <th>Nombre</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($categorias as $categoria): ?>
        <tr>
            <td><?= $categoria['nombre'] ?></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="<?= base_url('categorias/edit/'.$categoria['categoria_id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="<?= base_url('categorias/delete/'.$categoria['categoria_id']); ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Seguro que quieres eliminar esta categoría?')">Eliminar</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $this->endSection(); ?>
