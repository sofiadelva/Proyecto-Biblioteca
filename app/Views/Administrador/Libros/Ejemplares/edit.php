<?= $this->extend('Plantillas/plantilla_admin') ?>

<?= $this->section('titulo') ?>
Editar Ejemplar
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>

<form action="<?= base_url('ejemplares/update/'.$ejemplar['ejemplar_id']) ?>" method="post">
    <input type="hidden" name="libro_id" value="<?= $libro['libro_id'] ?>">

    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
            <option value="Disponible" <?= ($ejemplar['estado'] == 'Disponible') ? 'selected' : '' ?>>Disponible</option>
            <option value="No Diponible" <?= ($ejemplar['estado'] == 'No Diponible') ? 'selected' : '' ?>>No Diponible</option>
        </select>
    </div>

    <button type="submit" class="btn btn-warning">Actualizar</button>
    <a href="<?= base_url('ejemplares/listar/'.$libro['libro_id']); ?>" class="btn btn-secondary">Cancelar</a>
</form>
<?= $this->endSection() ?>
