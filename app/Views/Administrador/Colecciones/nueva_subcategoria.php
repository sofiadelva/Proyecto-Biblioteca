<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  

<?php $this->section('titulo'); ?> Agregar Subcategorías <?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>
<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">

    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-tags-fill me-2" style="color: #0C1E44;"></i>
        Registrar Nuevas Subcategorías
    </h2>
    
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('colecciones/guardar_subcategoria'); ?>" class="row g-4" autocomplete="off">
        
        <div class="col-md-6">
            <label class="form-label fw-bold">1. Seleccionar Colección <span class="text-danger">*</span></label>
            <select id="select-coleccion" class="form-select" required>
                <option value="">Seleccione...</option>
                <?php foreach($todas_colecciones as $col): ?>
                    <option value="<?= $col['coleccion_id'] ?>"><?= esc($col['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">2. Seleccionar Subgénero <span class="text-danger">*</span></label>
            <select name="subgenero_id" id="select-subgenero" class="form-select" disabled required>
                <option value="">Esperando colección...</option>
            </select>
        </div>

        <div class="col-md-12 mt-4">
            <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                3. Subcategorías a incorporar
                <button type="button" id="add-subcat" class="btn btn-sm btn-dark shadow-sm">
                    <i class="bi bi-plus-lg"></i> Añadir otra
                </button>
            </label>
            
            <div id="subcat-container">
                <div class="input-group mb-2">
                    <span class="input-group-text bg-white"><i class="bi bi-tag"></i></span>
                    <input type="text" name="subcategorias[]" class="form-control" placeholder="Nombre de la subcategoría" required>
                </div>
            </div>
        </div>

        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#A01E53;">
                <i class="bi bi-save-fill me-2"></i> Guardar Subcategorías
            </button>
            <a href="<?= base_url('colecciones'); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">Cancelar</a>
        </div>
    </form>
</div>

<script>
    // Lógica para cargar Subgéneros vía AJAX
    document.getElementById('select-coleccion').addEventListener('change', function() {
        const colId = this.value;
        const subSelect = document.getElementById('select-subgenero');
        
        subSelect.innerHTML = '<option value="">Cargando...</option>';
        subSelect.disabled = true;

        if (colId) {
            fetch(`<?= base_url('colecciones/get_subgeneros') ?>/${colId}`)
                .then(response => response.json())
                .then(data => {
                    subSelect.innerHTML = '<option value="">Seleccione un subgénero...</option>';
                    data.forEach(sg => {
                        subSelect.innerHTML += `<option value="${sg.subgenero_id}">${sg.nombre}</option>`;
                    });
                    subSelect.disabled = false;
                });
        } else {
            subSelect.innerHTML = '<option value="">Esperando colección...</option>';
        }
    });

    // Agregar campos dinámicos
    document.getElementById('add-subcat').addEventListener('click', function() {
        const container = document.getElementById('subcat-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2 animate__animated animate__fadeIn';
        div.innerHTML = `
            <span class="input-group-text bg-white"><i class="bi bi-tag"></i></span>
            <input type="text" name="subcategorias[]" class="form-control" placeholder="Otra subcategoría..." required>
            <button type="button" class="btn btn-outline-danger remove-item"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        div.querySelector('.remove-item').addEventListener('click', () => div.remove());
    });
</script>
<?php $this->endSection(); ?>