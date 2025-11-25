<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  
<!-- Extiende la plantilla base "plantilla_admin" para mantener un diseño consistente en toda la app -->

<?php $this->section('titulo'); ?>
Editar Categoría
<?php $this->endSection(); ?>
<!-- Define el título de la página como "Editar Categoría" -->

<?php $this->section('contenido'); ?>
<!-- Inicio de la sección de contenido principal -->

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">

    <!-- Título estilizado de la sección -->
    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-tag-fill me-2" style="color: #0C1E44;"></i>
        Editar Categoría
    </h2>

    <!-- Formulario para editar una categoría existente -->
    <form method="post" action="<?= base_url('categorias/update/'.$categoria['categoria_id']); ?>" class="row g-4" autocomplete="off">
        
        <div class="col-md-12">
            <label for="nombre" class="form-label fw-bold">Nombre de la Categoría <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
                   value="<?= esc($categoria['nombre']) ?>" 
                   placeholder="Ej: Ficción, Historia, Ciencia"
                   required>
        </div>

        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <!-- Botón para enviar el formulario y actualizar la categoría -->
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#A01E53;">
                <i class="bi bi-arrow-repeat me-2"></i> Actualizar Categoría
            </button>

            <!-- Botón para regresar a la lista de categorías sin guardar cambios -->
            <a href="<?= base_url('categorias'); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
                <i class="bi bi-x-circle-fill"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<!-- Estilos para consistencia visual -->
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

<?php $this->endSection(); ?>