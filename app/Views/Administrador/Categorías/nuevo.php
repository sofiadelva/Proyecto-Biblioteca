<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  
<!-- Extiende la plantilla principal "plantilla_admin" para heredar el diseño base -->

<?php $this->section('titulo'); ?>
Agregar Categoría
<?php $this->endSection(); ?>
<!-- Define el título de la página como "Agregar Categoría" -->

<?php $this->section('contenido'); ?>
<!-- Inicio de la sección de contenido de la página -->

<!-- Formulario para crear una nueva categoría -->
<form method="post" action="<?= base_url('categorias/store'); ?>">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Categoría</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>

    <!-- Botón para enviar el formulario y guardar la nueva categoría -->
    <button type="submit" class="btn btn-primary">Guardar</button>

    <!-- Botón para cancelar y volver al listado de categorías -->
    <a href="<?= base_url('categorias'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?php $this->endSection(); ?>
