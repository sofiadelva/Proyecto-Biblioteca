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
            <label for="select-subcategoria" class="form-label fw-bold">Subcategoría <span class="text-danger">*</span></label>
            <select class="form-control" name="subcategoria_id" id="select-subcategoria" disabled required> 
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
        var selectColeccion = $('#select-coleccion');
        var selectSubgenero = $('#select-subgenero');
        var selectSubcategoria = $('#select-subcategoria');
        
        // 1. Inicializar Select2 para Colecciones
        selectColeccion.select2({
            placeholder: "Buscar o seleccionar una Colección",
            allowClear: true,
            theme: "bootstrap4", 
            ajax: {
                url: '<?= base_url('libros/get_colecciones_json'); ?>', 
                dataType: 'json',
                delay: 250, 
                data: function (params) { return { term: params.term }; },
                processResults: function (data) { return { results: data.results }; },
                cache: true
            }
        });

        // 2. Lógica de Cascada: Colección -> Subgénero
        selectColeccion.on('change', function () {
            var coleccionId = $(this).val();
            
            // Limpiar y deshabilitar/habilitar selectores dependientes
            selectSubgenero.val(null).trigger('change');
            selectSubcategoria.val(null).trigger('change');
            selectSubcategoria.prop('disabled', true); // La subcategoría siempre se deshabilita hasta que haya subgénero

            if (coleccionId) {
                // Habilitar Subgénero
                selectSubgenero.prop('disabled', false);
                
                // Inicializar Subgénero con filtro
                selectSubgenero.select2({
                    placeholder: "Seleccionar Subgénero",
                    allowClear: true,
                    theme: "bootstrap4", 
                    ajax: {
                        url: '<?= base_url('libros/get_subgeneros_json'); ?>',
                        dataType: 'json',
                        delay: 250, 
                        data: function (params) {
                            return {
                                term: params.term,
                                coleccion_id: coleccionId 
                            };
                        },
                        processResults: function (data) {
                            // 🌟 Lógica de obligatoriedad condicional del Subgénero
                            // Revisamos si solo existe una opción y el nombre de esa opción es vacío (o NULL en la BD)
                            var hasOnlyNull = data.results.length === 1 && (data.results[0].text === '' || data.results[0].text.toUpperCase() === 'NULL');

                            var requiredSpan = $('.required-subgenero');
                            if (hasOnlyNull) {
                                requiredSpan.hide();
                                selectSubgenero.prop('required', false);
                            } else {
                                requiredSpan.show();
                                selectSubgenero.prop('required', true);
                            }

                            return { results: data.results };
                        },
                        cache: true
                    }
                });
            } else {
                // Deshabilitar Subgénero si no hay Colección
                selectSubgenero.prop('disabled', true);
                selectSubgenero.prop('required', true); // Vuelve a ser requerido si no hay colección
                $('.required-subgenero').show(); 
            }
        }).trigger('change'); // Llamar al change al cargar la página para inicializar estados

        // 3. Lógica de Cascada: Subgénero -> Subcategoría
        selectSubgenero.on('change', function () {
            var subgeneroId = $(this).val();
            
            selectSubcategoria.val(null).trigger('change');

            if (subgeneroId) {
                // Habilitar Subcategoría
                selectSubcategoria.prop('disabled', false);

                // Inicializar Subcategoría con filtro
                selectSubcategoria.select2({
                    placeholder: "Seleccionar Subcategoría",
                    allowClear: true,
                    theme: "bootstrap4", 
                    ajax: {
                        url: '<?= base_url('libros/get_subcategorias_json'); ?>',
                        dataType: 'json',
                        delay: 250, 
                        data: function (params) {
                            return {
                                term: params.term,
                                subgenero_id: subgeneroId 
                            };
                        },
                        processResults: function (data) { return { results: data.results }; },
                        cache: true
                    }
                });
            } else {
                // Deshabilitar Subcategoría si no hay Subgénero
                selectSubcategoria.prop('disabled', true);
            }
        });


        // 4. Manejo de Old Values (Restauración de formulario después de error de validación)
        var old_coleccion_id = '<?= old('coleccion_id_dummy') ?>';
        if (old_coleccion_id) {
            $.ajax({
                dataType: 'json',
                url: '<?= base_url('libros/get_colecciones_json'); ?>',
                data: { id: old_coleccion_id } 
            }).then(function (data) {
                var coleccion = data.results[0]; 
                if (coleccion) {
                    var newOption = new Option(coleccion.text, coleccion.id, true, true);
                    selectColeccion.append(newOption).trigger('change');
                    
                    // Trigger de Subgénero (para cargar su old value)
                    var old_subgenero_id = '<?= old('subgenero_id_dummy') ?>';
                    if (old_subgenero_id) {
                        // Creamos una opción temporal para que Select2 se inicialice correctamente con el valor
                        var newSubgeneroOption = new Option("Cargando Subgénero...", old_subgenero_id, true, true);
                        selectSubgenero.append(newSubgeneroOption).trigger('change');
                        
                        // Trigger de Subcategoría (para cargar su old value)
                        var old_subcategoria_id = '<?= old('subcategoria_id') ?>';
                        if (old_subcategoria_id) {
                             var newSubcategoriaOption = new Option("Cargando Subcategoría...", old_subcategoria_id, true, true);
                             selectSubcategoria.append(newSubcategoriaOption).trigger('change');
                        }
                    }
                }
            });
        }
    });
</script>
<?php 
$this->endSection(); 
?>