<?php echo $this->extend('Plantillas/plantilla_admin'); ?>

<?php $this->section('titulo'); ?>
Agregar Categoría
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<form method="post" action="<?= base_url('categorias/store'); ?>">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Categoría</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?= base_url('categorias'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?php $this->endSection(); ?>
