<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  
<?php $this->section('titulo'); ?>
Agregar Categoría
<?php $this->endSection(); ?>
<?php $this->section('contenido'); ?>
<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">

    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-tags-fill me-2" style="color: #206060;"></i>
        Registrar Nueva Categoría
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

    <form method="post" action="<?= base_url('categorias/store'); ?>" class="row g-4" autocomplete="off">
        
        <div class="col-md-12">
            <label for="nombre" class="form-label fw-bold">Nombre de la Categoría <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
                   value="<?= old('nombre') ?>" 
                   placeholder="Ej: Ficción, Historia, Ciencia"
                   required>
        </div>

        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#206060;">
                <i class="bi bi-save-fill me-2"></i> Guardar Categoría
            </button>

            <a href="<?= base_url('categorias'); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
                <i class="bi bi-x-circle-fill"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<style>
    .section-title {
        color: #206060;
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

<?php $this->endSection(); ?>