<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  

<?php $this->section('titulo'); ?> Agregar Subgéneros <?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>
<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">

    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-bookmark-plus-fill me-2" style="color: #0C1E44;"></i>
        Registrar Subgéneros en Colección Existente
    </h2>

    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger shadow-sm">
            <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> No se pudieron agregar los siguientes:</h6>
            <ul class="mb-0">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form method="post" action="<?= base_url('colecciones/guardar_subgenero'); ?>" class="row g-4" autocomplete="off">
        
        <div class="col-md-12">
            <label for="coleccion_id" class="form-label fw-bold">Seleccionar Colección <span class="text-danger">*</span></label>
            <select name="coleccion_id" id="coleccion_id" class="form-select" required>
                <option value="">Seleccione una colección...</option>
                <?php foreach($todas_colecciones as $col): ?>
                    <option value="<?= $col['coleccion_id'] ?>" <?= old('coleccion_id') == $col['coleccion_id'] ? 'selected' : '' ?>>
                        <?= esc($col['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-12 mt-4">
            <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                Subgéneros a incorporar
                <button type="button" id="add-sub" class="btn btn-sm btn-dark shadow-sm">
                    <i class="bi bi-plus-lg"></i> Añadir otro
                </button>
            </label>
            
            <div id="sub-container">
                <div class="input-group mb-2 sub-item">
                    <span class="input-group-text bg-white"><i class="bi bi-bookmark-plus"></i></span>
                    <input type="text" name="subgeneros[]" class="form-control" placeholder="Nombre del subgénero" required>
                </div>
            </div>
        </div>

        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#0C1E44;">
                <i class="bi bi-check-circle-fill me-2"></i> Guardar Subgéneros
            </button>
            <a href="<?= base_url('colecciones'); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('add-sub').addEventListener('click', function() {
        const container = document.getElementById('sub-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2 sub-item';
        div.innerHTML = `
            <span class="input-group-text bg-white"><i class="bi bi-bookmark-plus"></i></span>
            <input type="text" name="subgeneros[]" class="form-control" placeholder="Otro subgénero..." required>
            <button type="button" class="btn btn-outline-danger remove-sub"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        div.querySelector('.remove-sub').addEventListener('click', () => div.remove());
    });

    document.querySelector('form').addEventListener('submit', function(e) {
    const inputs = document.querySelectorAll('input[name="subcategorias[]"]');
    const valores = [];
    let duplicadoInterno = false;

    inputs.forEach(input => {
        const val = input.value.trim().toLowerCase();
        if (valores.includes(val)) {
            duplicadoInterno = true;
            input.style.borderColor = 'red'; // Marca el error visualmente
        }
        valores.push(val);
    });

    if (duplicadoInterno) {
        e.preventDefault(); // Detiene el envío del formulario
        alert("Has escrito subcategorías duplicadas en el formulario. Por favor, corrígelas.");
    }
});
</script>

<style>
    .section-title { color: #0C1E44; font-weight: 700; font-size: 1.75rem; }
    .form-control, .form-select { border-radius: 8px; padding: 12px 15px; border: 1px solid #ced4da; }
    .input-group-text { border-radius: 8px 0 0 8px; }
    .input-group > .form-control { border-radius: 0 8px 8px 0; }
</style>


<?php $this->endSection(); ?>