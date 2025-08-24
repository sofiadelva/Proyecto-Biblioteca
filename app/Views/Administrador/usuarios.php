<?php echo $this->extend('Plantillas/plantilla_admin'); ?>

<?php $this->section('titulo'); ?>
Usuarios
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>

<div class="mb-3">
    <a href="<?= base_url('usuarios/create'); ?>" class="btn btn-success">Agregar</a>
</div>

<table class="table table-hover table-bordered my-3">
    <thead class="table-dark">
        <tr>
            <th>Nombre</th>
            <th>Carne</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Contraseña</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario['nombre'] ?></td>
            <td><?= $usuario['carne'] ?></td>
            <td><?= $usuario['correo'] ?></td>
            <td><?= $usuario['rol'] ?></td>
            <td><?= $usuario['password'] ?></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="<?= base_url('usuarios/edit/'.$usuario['usuario_id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="<?= base_url('usuarios/delete/'.$usuario['usuario_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">Eliminar</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $this->endSection(); ?>
