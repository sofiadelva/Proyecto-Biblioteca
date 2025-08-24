<?php echo $this->extend('Plantillas/plantilla_admin'); ?>

<?php $this->section('titulo'); ?>
Editar Categoría
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<form method="post" action="<?= base_url('categorias/update/'.$categoria['categoria_id']); ?>">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Categoría</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $categoria['nombre'] ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="<?= base_url('categorias'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?php $this->endSection(); ?>
