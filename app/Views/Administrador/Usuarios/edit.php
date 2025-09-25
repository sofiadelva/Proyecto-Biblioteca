<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  
<!-- Extiende la plantilla base. -->

<?php $this->section('titulo'); ?>
Editar Usuario
<?php $this->endSection(); ?>  
<!-- Título de la vista. -->

<?php $this->section('contenido'); ?>  
<!-- Contenido principal. -->

<form method="post" action="<?= base_url('usuarios/update/'.$usuario['usuario_id']); ?>">
    <!-- Formulario que envía los datos al método update con el ID del usuario. -->

    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= $usuario['nombre'] ?>" required>
    </div>
    <!-- Campo para modificar el nombre del usuario. -->

    <div class="mb-3">
        <label>Carne</label>
        <input type="number" name="carne" class="form-control" value="<?= $usuario['carne'] ?>" required>
    </div>
    <!-- Campo para modificar el número de carne del usuario. -->

    <div class="mb-3">
        <label>Correo</label>
        <input type="email" name="correo" class="form-control" value="<?= $usuario['correo'] ?>" required>
    </div>
    <!-- Campo para modificar el correo del usuario. -->

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
    <!-- Selector para cambiar el rol del usuario, marcando el rol actual. -->

    <div class="mb-3">
        <label>Contraseña (dejar vacío para no cambiar)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <!-- Campo opcional para cambiar la contraseña. Si se deja vacío, no se actualiza. -->

    <button type="submit" class="btn btn-warning">Actualizar</button>
    <!-- Botón para enviar el formulario y actualizar los datos del usuario. -->
</form>

<?php $this->endSection(); ?>  
<!-- Fin de la sección de contenido. -->
