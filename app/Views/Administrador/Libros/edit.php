<?php echo $this->extend('plantilla'); ?>
<?php $this->section('contenido'); ?>

<h3 class="my-3">Editar Libro</h3>

<form action="<?= base_url('libros/update/'.$libro['libro_id']); ?>" method="post" class="row g-3" autocomplete="off">

    <div class="col-md-6">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control" name="titulo" value="<?= $libro['titulo'] ?>" required>
    </div>

    <div class="col-md-6">
        <label for="autor" class="form-label">Autor</label>
        <input type="text" class="form-control" name="autor" value="<?= $libro['autor'] ?>" required>
    </div>

    <div class="col-md-6">
        <label for="editorial" class="form-label">Editorial</label>
        <input type="text" class="form-control" name="editorial" value="<?= $libro['editorial'] ?>">
    </div>

    <div class="col-md-3">
        <label for="cantidad_total" class="form-label">Cantidad Total</label>
        <input type="number" class="form-control" name="cantidad_total" value="<?= $libro['cantidad_total'] ?>" required>
    </div>

    <div class="col-md-3">
        <label for="cantidad_disponibles" class="form-label">Disponibles</label>
        <input type="number" class="form-control" name="cantidad_disponibles" value="<?= $libro['cantidad_disponibles'] ?>" required>
    </div>

    <div class="col-md-6">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-select" name="estado" required>
            <option value="Disponible" <?= $libro['estado']=="Disponible" ? 'selected':''; ?>>Disponible</option>
            <option value="Prestado" <?= $libro['estado']=="Prestado" ? 'selected':''; ?>>Prestado</option>
            <option value="Dañado" <?= $libro['estado']=="Dañado" ? 'selected':''; ?>>Dañado</option>
        </select>
    </div>

    <div class="col-12">
        <a href="<?= base_url('libros'); ?>" class="btn btn-secondary">Regresar</a>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </div>
</form>

<?php $this->endSection(); ?>
