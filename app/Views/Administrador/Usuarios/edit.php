<?php echo $this->extend('Plantillas/plantilla_admin'); ?>

<?php $this->section('titulo'); ?>
Editar Usuario
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>
<form method="post" action="<?= base_url('usuarios/update/'.$usuario['usuario_id']); ?>">
    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= $usuario['nombre'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Carne</label>
        <input type="number" name="carne" class="form-control" value="<?= $usuario['carne'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Correo</label>
        <input type="email" name="correo" class="form-control" value="<?= $usuario['correo'] ?>" required>
    </div>
   <div class="mb-3">
        <label for="rol" class="form-label">Rol</label>
        <select class="form-select" name="rol" required>
            <option value="">Seleccionar</option>
            <option value="Administrador" <?= ($usuario['rol'] == 'Administrador') ? 'selected' : '' ?>>Administrador</option>
            <option value="Bibliotecario" <?= ($usuario['rol'] == 'Bibliotecario') ? 'selected' : '' ?>>Bibliotecario</option>
            <option value="Alumno" <?= ($usuario['rol'] == 'Alumno') ? 'selected' : '' ?>>Alumno</option>
            <option value="Maestro" <?= ($usuario['rol'] == 'Maestro') ? 'selected' : '' ?>>Maestro</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Contraseña (dejar vacío para no cambiar)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <button type="submit" class="btn btn-warning">Actualizar</button>
</form>
<?php $this->endSection(); ?>
