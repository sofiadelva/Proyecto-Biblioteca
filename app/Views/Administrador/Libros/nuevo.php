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
        
        <div class="col-md-4">
            <label for="titulo" class="form-label fw-bold">Título <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="titulo" value="<?= old('titulo') ?>" required>
        </div>

        <div class="col-md-4">
            <label for="autor" class="form-label fw-bold">Autor <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="autor" value="<?= old('autor') ?>" required>
        </div>
        
        <div class="col-md-4">
            <label for="codigo" class="form-label fw-bold">Código (ISBN/Interno) <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="codigo" value="<?= old('codigo') ?>" required>
        </div>

        <div class="col-md-4">
            <label for="editorial" class="form-label fw-bold">Editorial</label>
            <input type="text" class="form-control" name="editorial" value="<?= old('editorial') ?>">
        </div>

        <div class="col-md-4">
            <label for="ano" class="form-label fw-bold">Año de Publicación</label>
            <input type="number" class="form-control" name="ano" value="<?= old('ano') ?? date('Y') ?>" min="1900" max="<?= date('Y') ?>">
        </div>
        
        <div class="col-md-4">
            <label for="paginas" class="form-label fw-bold">Número de Páginas</label>
            <input type="number" class="form-control" name="paginas" value="<?= old('paginas') ?>" min="1">
        </div>

        <h5 class="mt-4 pt-3 border-top w-100">Clasificación</h5>

        <div class="col-md-4">
            <label for="select-coleccion" class="form-label fw-bold">Colección <span class="text-danger">*</span></label>
            <select class="form-control" name="coleccion_id_dummy" id="select-coleccion" required> 
                <option value="">Seleccionar Colección</option>
            </select>
        </div>
        
        <div class="col-md-4">
            <label for="select-subgenero" class="form-label fw-bold">Subgénero <span class="text-danger required-subgenero">*</span></label>
            <select class="form-control" name="subgenero_id_dummy" id="select-subgenero" disabled required> 
                <option value="">Seleccionar Subgénero</option>
            </select>
        </div>
        
        <div class="col-md-4">
            <label for="select-subcategoria" class="form-label fw-bold">Subcategoría </label>
            <select class="form-control" name="subcategoria_id" id="select-subcategoria" disabled> 
                <option value="">Seleccionar Subcategoría</option>
            </select>
        </div>


        <h5 class="mt-4 pt-3 border-top w-100">Inventario</h5>

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
// ⭐️ SECCIÓN DE SCRIPTS: Inicialización de Select2 con búsqueda dinámica y cascada
$this->section('scripts'); 
?>
<script>
$(document).ready(function() {
    const selectCol = $('#select-coleccion');
    const selectSubG = $('#select-subgenero');
    const selectSubC = $('#select-subcategoria');

    // Configuración común de Select2
    const commonOptions = {
        theme: "bootstrap4",
        width: '100%',
        allowClear: true,
        delay: 250
    };

    // 1. Inicializar Colección
    selectCol.select2({
        ...commonOptions,
        placeholder: "Buscar Colección...",
        ajax: {
            url: '<?= site_url('libros/get_colecciones_json'); ?>',
            dataType: 'json',
            data: params => ({ term: params.term }),
            processResults: data => ({ results: data.results })
        }
    });

    // 2. Evento Cambio Colección -> Carga Subgénero
    selectCol.on('change', function() {
        const colId = $(this).val();
        selectSubG.val(null).trigger('change').prop('disabled', !colId);
        selectSubC.val(null).trigger('change').prop('disabled', true);

        if (colId) {
            selectSubG.select2({
                ...commonOptions,
                placeholder: "Seleccionar Subgénero",
                ajax: {
                    url: '<?= site_url('libros/get_subgeneros_json'); ?>',
                    dataType: 'json',
                    data: params => ({ term: params.term, coleccion_id: colId }),
                    processResults: data => ({ results: data.results })
                }
            });
        }
    });

    // 3. Evento Cambio Subgénero -> Carga Subcategoría
    selectSubG.on('change', function() {
        const subGId = $(this).val();
        selectSubC.val(null).trigger('change').prop('disabled', !subGId);

        if (subGId) {
            selectSubC.select2({
                ...commonOptions,
                placeholder: "Seleccionar Subcategoría",
                ajax: {
                    url: '<?= site_url('libros/get_subcategorias_json'); ?>',
                    dataType: 'json',
                    data: params => ({ term: params.term, subgenero_id: subGId }),
                    processResults: data => ({ results: data.results })
                }
            });
        }
    });

    // 4. Restauración de valores previos (Old values)
    const oldCol = '<?= old('coleccion_id_dummy') ?>';
    const oldSubG = '<?= old('subgenero_id_dummy') ?>';
    const oldSubC = '<?= old('subcategoria_id') ?>';

    if (oldCol) {
        $.get('<?= site_url('libros/get_colecciones_json'); ?>', { id: oldCol }, function(data) {
            if (data.results.length) {
                const opt = new Option(data.results[0].text, data.results[0].id, true, true);
                selectCol.append(opt).trigger('change');
                
                if (oldSubG) {
                    $.get('<?= site_url('libros/get_subgeneros_json'); ?>', { id: oldSubG }, function(dataG) {
                        if (dataG.results.length) {
                            const optG = new Option(dataG.results[0].text, dataG.results[0].id, true, true);
                            selectSubG.append(optG).trigger('change');
                            
                            if (oldSubC) {
                                $.get('<?= site_url('libros/get_subcategorias_json'); ?>', { id: oldSubC }, function(dataC) {
                                    if (dataC.results.length) {
                                        const optC = new Option(dataC.results[0].text, dataC.results[0].id, true, true);
                                        selectSubC.append(optC).trigger('change');
                                    }
                                });
                            }
                        }
                    });
                }
            }
        });
    }
});
</script>
<?php 
$this->endSection(); 
?>