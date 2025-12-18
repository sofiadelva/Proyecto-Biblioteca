<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  

<?php $this->section('titulo'); ?>
Editar Colección
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">

    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-pencil-square me-2" style="color: #0C1E44;"></i>
        Editar Colección
    </h2>

    <form method="post" action="<?= base_url('colecciones/update/'.$coleccion['coleccion_id']); ?>" class="row g-4" autocomplete="off">
        
        <div class="col-md-12">
            <label for="nombre" class="form-label fw-bold">Nombre de la Colección <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
                   value="<?= esc($coleccion['nombre']) ?>" 
                   placeholder="Ej: Narrativa, Dramático..."
                   required>
        </div>

        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#A01E53;">
                <i class="bi bi-arrow-repeat me-2"></i> Actualizar Colección
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
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        transition: background-color 0.2s;
    }
</style>

<?php $this->endSection(); ?>