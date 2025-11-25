<?= $this->extend('Plantillas/plantilla_admin') ?> 
<?= $this->section('titulo') ?>
Agregar Ejemplar
<?= $this->endSection() ?> 
<?= $this->section('contenido') ?> 
<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">

    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-plus-circle-fill me-2" style="color: #0C1E44;"></i>
        Agregar Nuevo Ejemplar al Libro: <span style="font-weight: 500;"><?= esc($libro['titulo']) ?></span>
    </h2>

    <form action="<?= base_url('ejemplares/create') ?>" method="post" class="row g-4" autocomplete="off">
        
        <input type="hidden" name="libro_id" value="<?= $libro['libro_id'] ?>">
        <div class="col-md-6">
            <label for="titulo_libro" class="form-label fw-bold text-muted">Libro:</label>
            <input type="text" class="form-control" value="<?= esc($libro['titulo']) ?>" readonly disabled>
        </div>

        <div class="col-md-6">
            <label for="estado" class="form-label fw-bold">Estado <span class="text-danger">*</span></label>
            <select name="estado" class="form-select" required>
                <option value="" selected disabled>Seleccionar estado</option>
                <option value="Disponible" <?= old('estado') == 'Disponible' ? 'selected' : '' ?>>Disponible</option>
                <option value="Dañado" <?= old('estado') == 'Dañado' ? 'selected' : '' ?>>Dañado</option>
            </select>
        </div>

        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#A01E53;">
                <i class="bi bi-save-fill me-2"></i> Guardar Ejemplar
            </button>
            <a href="<?= base_url('ejemplares/listar/'.$libro['libro_id']); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
                <i class="bi bi-x-circle-fill"></i> Cancelar
            </a>
        </div>
        </form>
</div>

<style>
    .section-title {
        color: #0C1E44;
        font-weight: 700;
        font-size: 1.75rem;
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 15px;
        box-shadow: none !important;
        border: 1px solid #ced4da;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        transition: background-color 0.2s;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
</style>

<?= $this->endSection() ?> 

