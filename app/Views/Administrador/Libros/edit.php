<?php 
// Extiende de la plantilla principal llamada "plantilla_admin"
echo $this->extend('Plantillas/plantilla_admin'); 
?>

<?php 
// Define la sección "titulo" de la plantilla
$this->section('titulo'); 
?>
Editar Libro
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido" que se mostrará en el layout
$this->section('contenido'); 
?>

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    
    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-pencil-square me-2" style="color: #0C1E44;"></i>
        Modificar Información del Libro
    </h2>
    
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('msg')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('libros/update/'.$libro['libro_id']); ?>" method="post" class="row g-4" autocomplete="off">
        
        <div class="col-md-6">
            <label for="titulo" class="form-label fw-bold">Título <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="titulo" value="<?= old('titulo', $libro['titulo']) ?>" required>
        </div>

        <div class="col-md-6">
            <label for="autor" class="form-label fw-bold">Autor <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="autor" value="<?= old('autor', $libro['autor']) ?>" required>
        </div>

        <div class="col-md-6">
            <label for="editorial" class="form-label fw-bold">Editorial</label>
            <input type="text" class="form-control" name="editorial" value="<?= old('editorial', $libro['editorial']) ?>">
        </div>

        <div class="col-md-3">
            <label for="cantidad_total" class="form-label fw-bold">Cantidad Total <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="cantidad_total" name="cantidad_total" value="<?= old('cantidad_total', $libro['cantidad_total']) ?>" required min="1">
        </div>

        <div class="col-md-3">
            <label for="cantidad_disponibles" class="form-label fw-bold">Disponibles <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="cantidad_disponibles" value="<?= old('cantidad_disponibles', $libro['cantidad_disponibles']) ?>" required min="0" max="<?= $libro['cantidad_total'] ?>">
            <small class="form-text text-muted">No puede ser mayor que la Cantidad Total.</small>
        </div>

        <div class="col-md-6">
            <label for="estado" class="form-label fw-bold">Estado <span class="text-danger">*</span></label>
            <select class="form-select" name="estado" required>
                <option value="Disponible" <?= old('estado', $libro['estado']) == "Disponible" ? 'selected':''; ?>>Disponible</option>
                <option value="Dañado" <?= old('estado', $libro['estado']) == "Dañado" ? 'selected':''; ?>>Dañado</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="categoria_id" class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
            <select class="form-select" name="categoria_id" required>
                <option value="">Seleccionar Categoría</option>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['categoria_id']; ?>"
                        <?= old('categoria_id', $libro['categoria_id']) == $cat['categoria_id'] ? 'selected' : '' ?>>
                        <?= $cat['nombre']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <a href="<?= base_url('libros'); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
                <i class="bi bi-arrow-left-short"></i> Regresar
            </a>
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#A01E53;">
                <i class="bi bi-save-fill me-2"></i> Actualizar Libro
            </button>
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

<?php 
$this->endSection(); 
?>