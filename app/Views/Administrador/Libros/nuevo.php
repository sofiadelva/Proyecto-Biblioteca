<?php echo $this->extend('plantilla'); ?>
<?php $this->section('contenido'); ?>

<h3 class="my-3">Nuevo Libro</h3>

<form action="<?= base_url('libros/create'); ?>" method="post" class="row g-3" autocomplete="off">

    <div class="col-md-6">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control" name="titulo" required>
    </div>

    <div class="col-md-6">
        <label for="autor" class="form-label">Autor</label>
        <input type="text" class="form-control" name="autor" required>
    </div>

    <div class="col-md-6">
        <label for="editorial" class="form-label">Editorial</label>
        <input type="text" class="form-control" name="editorial">
    </div>

    <div class="col-md-3">
        <label for="cantidad_total" class="form-label">Cantidad Total</label>
        <input type="number" class="form-control" name="cantidad_total" required>
    </div>

    <div class="col-md-3">
        <label for="cantidad_disponibles" class="form-label">Disponibles</label>
        <input type="number" class="form-control" name="cantidad_disponibles" required>
    </div>

    <div class="col-md-6">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-select" name="estado" required>
            <option value="">Seleccionar</option>
            <option value="Disponible">Disponible</option>
            <option value="Dañado">Dañado</option>
        </select>
    </div>

    <div class="col-12">
        <a href="<?= base_url('libros'); ?>" class="btn btn-secondary">Regresar</a>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>

<?php $this->endSection(); ?>
