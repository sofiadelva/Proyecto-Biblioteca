<?= $this->extend('Plantillas/plantilla_admin'); ?>

<?= $this->section('titulo'); ?>
Editar Libro
<?= $this->endSection(); ?>

<?= $this->section('contenido'); ?>

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    
    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-pencil-square me-2" style="color: #0C1E44;"></i>
        Modificar Información del Libro
    </h2>
    
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('libros/update/'.$libro['libro_id']); ?>" method="post" class="row g-4" autocomplete="off">
        
        <div class="col-md-4">
            <label for="codigo" class="form-label fw-bold">Código (ISBN/Interno) <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="codigo" value="<?= old('codigo', $libro['codigo']) ?>" required>
        </div>

        <div class="col-md-8">
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

        <div class="col-md-4">
            <label for="ano" class="form-label fw-bold">Año de Publicación</label>
            <input type="number" class="form-control" name="ano" value="<?= old('ano', $libro['ano']) ?>">
        </div>

        <div class="col-md-4">
            <label for="paginas" class="form-label fw-bold">Nº de Páginas</label>
            <input type="number" class="form-control" name="paginas" value="<?= old('paginas', $libro['paginas']) ?>">
        </div>

        <div class="col-md-4">
            <label for="estado" class="form-label fw-bold">Estado General <span class="text-danger">*</span></label>
            <select class="form-select" name="estado" required>
                <option value="Disponible" <?= old('estado', $libro['estado']) == "Disponible" ? 'selected':''; ?>>Disponible</option>
                <option value="Dañado" <?= old('estado', $libro['estado']) == "Dañado" ? 'selected':''; ?>>Dañado</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold text-primary">1. Colección</label>
            <select id="coleccion_id" name="coleccion_id_dummy" class="form-select select2-ajax">
                <?php if(isset($coleccion_id)): ?>
                    <option value="<?= $coleccion_id ?>" selected><?= $coleccion_nombre ?></option>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold text-primary">2. Subgénero</label>
            <select id="subgenero_id" name="subgenero_id_dummy" class="form-select select2-ajax">
                <?php if(isset($subgenero_id)): ?>
                    <option value="<?= $subgenero_id ?>" selected><?= $subgenero_nombre ?></option>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold text-primary">3. Subcategoría</label>
            <select name="subcategoria_id" id="subcategoria_id" class="form-select select2-ajax">
                <?php if(!empty($libro['subcategoria_id'])): ?>
                    <option value="<?= $libro['subcategoria_id'] ?>" selected><?= $libro['subcategoria_nombre'] ?></option>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="cantidad_total" class="form-label fw-bold">Cantidad Total <span class="text-danger">*</span></label>
            <input type="number" class="form-control bg-light" id="cantidad_total" name="cantidad_total" value="<?= old('cantidad_total', $libro['cantidad_total']) ?>" readonly>
            <small class="text-muted italic">Para cambiar el stock total, use el módulo de ejemplares.</small>
        </div>

        <div class="col-md-6">
            <label for="cantidad_disponibles" class="form-label fw-bold">Disponibles <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="cantidad_disponibles" value="<?= old('cantidad_disponibles', $libro['cantidad_disponibles']) ?>" required min="0" max="<?= $libro['cantidad_total'] ?>">
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
    .section-title { color: #0C1E44; font-weight: 700; font-size: 1.75rem; }
    .form-control, .form-select { border-radius: 8px; padding: 10px 15px; border: 1px solid #ced4da; }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Forzamos el tema a bootstrap-5 o default si no carga el css de tema
    const s2Options = {
        width: '100%',
        allowClear: true,
        placeholder: "Seleccionar opción"
    };

    function initSelect2(selector, url, parentSelector = null, parentParam = '') {
        $(selector).select2({
            ...s2Options,
            ajax: {
                url: '<?= base_url() ?>/' + url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    let query = { term: params.term };
                    if(parentSelector) query[parentParam] = $(parentSelector).val();
                    return query;
                },
                processResults: data => ({ results: data.results })
            }
        });
    }

    // Inicialización
    initSelect2('#coleccion_id', 'libros/get_colecciones_json');
    initSelect2('#subgenero_id', 'libros/get_subgeneros_json', '#coleccion_id', 'coleccion_id');
    initSelect2('#subcategoria_id', 'libros/get_subcategorias_json', '#subgenero_id', 'subgenero_id');

    // Lógica de limpieza en cascada
    $('#coleccion_id').on('change', function() {
        $('#subgenero_id').val(null).trigger('change');
        $('#subcategoria_id').val(null).trigger('change');
    });

    $('#subgenero_id').on('change', function() {
        $('#subcategoria_id').val(null).trigger('change');
    });
});
</script>

<?= $this->endSection(); ?>