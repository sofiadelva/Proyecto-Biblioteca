<?php echo $this->extend('Plantillas/plantilla_admin'); ?>

<?php $this->section('titulo'); ?>
Agregar Usuario
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>
<form method="post" action="<?= base_url('usuarios/store'); ?>">
    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Carne</label>
        <input type="number" name="carne" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Correo</label>
        <input type="email" name="correo" class="form-control" required>
    </div>
    <div class="md-3">
        <label for="estado" class="form-label">Rol</label>
        <select class="form-select" name="estado" required>
            <option value="">Seleccionar</option>
            <option value="Disponible">Administrador</option>
            <option value="Dañado">Bibliotecario</option>
            <option value="Disponible">Alumno</option>
            <option value="Dañado">Maestro</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Agregar</button>
</form>
<?php $this->endSection(); ?>
