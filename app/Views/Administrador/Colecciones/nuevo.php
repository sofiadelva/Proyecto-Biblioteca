<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  

<?php $this->section('titulo'); ?>
Agregar Colección
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>
<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">

    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-collection-fill me-2" style="color: #0C1E44;"></i>
        Registrar Nueva Colección
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

    <form method="post" action="<?= base_url('colecciones/store'); ?>" class="row g-4" autocomplete="off">
        
        <div class="col-md-12">
            <label for="nombre" class="form-label fw-bold">Nombre de la Colección <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
                   value="<?= old('nombre') ?>" 
                   placeholder="Escribe aquí..."
                   required>
            <div class="form-text">Este es el nivel principal de la clasificación bibliográfica.</div>
        </div>

        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#A01E53;">
                <i class="bi bi-save-fill me-2"></i> Guardar Colección
            </button>

            <a href="<?= base_url('colecciones'); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
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
    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        box-shadow: none !important;
        border: 1px solid #ced4da;
    }
    .form-control:focus {
        border-color: #0C1E44;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        transition: background-color 0.2s;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>
<?php $this->endSection(); ?>