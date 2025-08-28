<?= $this->extend('Plantillas/plantilla_admin') ?>

<?= $this->section('titulo') ?>
Agregar Ejemplar
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<h3>Agregar nuevo ejemplar al libro: <?= esc($libro['titulo']) ?></h3>

<form action="<?= base_url('ejemplares/create') ?>" method="post">
    <input type="hidden" name="libro_id" value="<?= $libro['libro_id'] ?>">

    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
            <option value="">Seleccionar estado</option>
            <option value="Disponible">Disponible</option>
            <option value="No Disponible">No Disponible</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="<?= base_url('ejemplares/listar/'.$libro['libro_id']); ?>" class="btn btn-secondary">Cancelar</a>
</form>
<?= $this->endSection() ?>
