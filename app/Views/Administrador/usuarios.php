<?php echo $this->extend('Plantillas/plantilla_admin'); ?> 
<!-- Indica que esta vista extiende la plantilla principal "plantilla_admin" -->

<?php $this->section('titulo'); ?>
Usuarios
<?php $this->endSection(); ?>
<!-- Define el título de la página. -->

<?php $this->section('contenido'); ?>
<!-- Inicio de la sección de contenido principal -->

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>

<div class="mb-3">
    <!-- Botón para ir a la vista de creación de un nuevo usuario -->
    <a href="<?= base_url('usuarios/create'); ?>" class="btn btn-success">Agregar</a>
</div>

<!-- Tabla que muestra la lista de usuarios registrados -->
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
        <!-- Se recorre el arreglo de usuarios enviado desde el controlador -->
        <?php foreach($usuarios as $usuario): ?>
        <tr>
            <!-- Se muestran los datos de cada usuario -->
            <td><?= $usuario['nombre'] ?></td>
            <td><?= $usuario['carne'] ?></td>
            <td><?= $usuario['correo'] ?></td>
            <td><?= $usuario['rol'] ?></td>
            <td><?= $usuario['password'] ?></td>
            <td>
                <div class="d-flex gap-1">
                    <!-- Botón para editar al usuario -->
                    <a href="<?= base_url('usuarios/edit/'.$usuario['usuario_id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    
                    <!-- Botón para eliminar al usuario con confirmación -->
                    <a href="<?= base_url('usuarios/delete/'.$usuario['usuario_id']); ?>" class="btn btn-danger btn-sm" 
                       onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">Eliminar</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $this->endSection(); ?>
<!-- Fin de la sección de contenido -->
