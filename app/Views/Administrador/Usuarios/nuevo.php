<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  
<!-- Extiende la plantilla base. -->

<?php $this->section('titulo'); ?>
Agregar Usuario
<?php $this->endSection(); ?>  
<!-- Título de la página. -->

<?php $this->section('contenido'); ?>  
<!-- Contenido principal. -->

<form method="post" action="<?= base_url('usuarios/store'); ?>">
    <!-- Formulario que envía los datos al método store para crear un nuevo usuario. -->

    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>
    <!-- Campo para ingresar el nombre del usuario. -->

    <div class="mb-3">
        <label>Carne</label>
        <input type="number" name="carne" class="form-control" required>
    </div>
    <!-- Campo para ingresar el número de carne del usuario. -->

    <div class="mb-3">
        <label>Correo</label>
        <input type="email" name="correo" class="form-control" required>
    </div>
    <!-- Campo para ingresar el correo del usuario. -->

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
    <!-- Selector para asignar el rol del usuario. Nota: los values no coinciden con los nombres de roles. -->

    <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <!-- Campo para asignar la contraseña al usuario. -->

    <button type="submit" class="btn btn-success">Agregar</button>
    <!-- Botón para enviar el formulario y crear el usuario. -->
</form>

<?php $this->endSection(); ?>  
<!-- Fin de la sección de contenido. -->
