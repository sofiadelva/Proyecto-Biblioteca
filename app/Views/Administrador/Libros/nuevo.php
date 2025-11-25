<?php 
// Extiende de la plantilla principal llamada "plantilla_admin"
echo $this->extend('Plantillas/plantilla_admin'); 
?>

<?php 
// Define la sección "titulo" de la plantilla
$this->section('titulo'); 
?>
Agregar Nuevo Libro
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido" que se mostrará en el layout
$this->section('contenido'); 
?>

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    
    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-book-half me-2" style="color: #0C1E44;"></i>
        Registrar Nuevo Libro
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

    <form action="<?= base_url('libros/create'); ?>" method="post" class="row g-4" autocomplete="off">
        
        <div class="col-md-6">
            <label for="titulo" class="form-label fw-bold">Título <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="titulo" value="<?= old('titulo') ?>" required>
        </div>

        <div class="col-md-6">
            <label for="autor" class="form-label fw-bold">Autor <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="autor" value="<?= old('autor') ?>" required>
        </div>

        <div class="col-md-6">
            <label for="editorial" class="form-label fw-bold">Editorial</label>
            <input type="text" class="form-control" name="editorial" value="<?= old('editorial') ?>">
        </div>

        <div class="col-md-3">
            <label for="cantidad_total" class="form-label fw-bold">Cantidad Total <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="cantidad_total" name="cantidad_total" value="<?= old('cantidad_total') ?? 1 ?>" required min="1">
        </div>

        <div class="col-md-3">
            <label for="cantidad_disponibles" class="form-label fw-bold">Disponibles <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="cantidad_disponibles" value="<?= old('cantidad_disponibles') ?? 1 ?>" required min="0">
            <small class="form-text text-muted">Debe ser igual o menor que la Cantidad Total.</small>
        </div>

        <div class="col-md-6">
            <label for="estado" class="form-label fw-bold">Estado <span class="text-danger">*</span></label>
            <select class="form-select" name="estado" required>
                <option value="Disponible" <?= old('estado') == "Disponible" || old('estado') === null ? 'selected':''; ?>>Disponible</option>
                <option value="Dañado" <?= old('estado') == "Dañado" ? 'selected':''; ?>>Dañado</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="select-categoria" class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
            
            <select class="form-control" name="categoria_id" id="select-categoria" required> 
                <option value="<?= old('categoria_id') ?>" selected><?= old('categoria_id') ? 'Cargando Categoría...' : 'Seleccionar Categoría' ?></option>
            </select>
        </div>

        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <a href="<?= base_url('libros'); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
                <i class="bi bi-arrow-left-short"></i> Regresar
            </a>
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#A01E53;">
                <i class="bi bi-plus-circle-fill me-2"></i> Guardar Libro
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

<?php 
// ⭐️ SECCIÓN DE SCRIPTS: Inicialización de Select2 con búsqueda dinámica
$this->section('scripts'); 
?>
<script>
    $(document).ready(function() {
        var selectCategoria = $('#select-categoria');
        
        selectCategoria.select2({
            placeholder: "Buscar o seleccionar una categoría",
            allowClear: true,
            theme: "bootstrap4", 
            ajax: {
                url: '<?= base_url('libros/get_categorias_json'); ?>', 
                dataType: 'json',
                delay: 250, 
                data: function (params) {
                    return {
                        term: params.term, 
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: false 
                        }
                    };
                },
                cache: true
            }
        });
        
        // Cargar el valor antiguo si existe (para manejar errores de validación)
        var old_category_id = '<?= old('categoria_id') ?>';
        if (old_category_id) {
             $.ajax({
                dataType: 'json',
                url: '<?= base_url('libros/get_categorias_json'); ?>',
                data: { term: '', id: old_category_id } 
            }).then(function (data) {
                // El controlador devuelve un array de resultados, tomamos el primero
                var category = data.results[0]; 
                if (category) {
                    var newOption = new Option(category.text, category.id, true, true);
                    selectCategoria.append(newOption).trigger('change');
                }
            });
        }
    });
</script>
<?php 
$this->endSection(); 
?>