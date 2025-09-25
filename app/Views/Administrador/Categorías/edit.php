<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  
<!-- Extiende la plantilla base "plantilla_admin" para mantener un diseño consistente en toda la app -->

<?php $this->section('titulo'); ?>
Editar Categoría
<?php $this->endSection(); ?>
<!-- Define el título de la página como "Editar Categoría" -->

<?php $this->section('contenido'); ?>
<!-- Inicio de la sección de contenido principal -->

<!-- Formulario para editar una categoría existente -->
<form method="post" action="<?= base_url('categorias/update/'.$categoria['categoria_id']); ?>">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Categoría</label>
        <input type="text" class="form-control" id="nombre" name="nombre" 
               value="<?= $categoria['nombre'] ?>" required>
    </div>

    <!-- Botón para enviar el formulario y actualizar la categoría -->
    <button type="submit" class="btn btn-primary">Actualizar</button>

    <!-- Botón para regresar a la lista de categorías sin guardar cambios -->
    <a href="<?= base_url('categorias'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?php $this->endSection(); ?>
