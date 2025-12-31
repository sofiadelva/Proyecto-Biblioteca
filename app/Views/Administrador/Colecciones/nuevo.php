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
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
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
                   placeholder="Ej: Literatura, Ciencias Exactas..."
                   required>
        </div>

        <div class="col-md-12 mt-4">
            <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                Subgéneros correspondientes <span class="text-danger">* (Mínimo uno)</span>
                <button type="button" id="add-subgenero" class="btn btn-sm btn-dark shadow-sm">
                    <i class="bi bi-plus-lg"></i> Añadir otro
                </button>
            </label>
            
            <div id="subgeneros-container">
                <div class="input-group mb-2 subgenero-item">
                    <span class="input-group-text bg-white"><i class="bi bi-bookmark"></i></span>
                    <input type="text" name="subgeneros[]" class="form-control" placeholder="Nombre del subgénero" required>
                    </div>
            </div>
            

        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#A01E53;">
                <i class="bi bi-save-fill me-2"></i> Guardar y Crear
            </button>

            <a href="<?= base_url('colecciones'); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
                <i class="bi bi-x-circle-fill"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('add-subgenero').addEventListener('click', function() {
        const container = document.getElementById('subgeneros-container');
        const newItem = document.createElement('div');
        newItem.className = 'input-group mb-2 subgenero-item animate__animated animate__fadeIn';
        newItem.innerHTML = `
            <span class="input-group-text bg-white"><i class="bi bi-bookmark"></i></span>
            <input type="text" name="subgeneros[]" class="form-control" placeholder="Otro subgénero..." required>
            <button type="button" class="btn btn-outline-danger remove-subgenero">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(newItem);

        // Lógica para eliminar el campo
        newItem.querySelector('.remove-subgenero').addEventListener('click', function() {
            newItem.remove();
        });
    });
</script>

<style>
    .section-title { color: #0C1E44; font-weight: 700; font-size: 1.75rem; }
    .form-control { border-radius: 8px; padding: 12px 15px; border: 1px solid #ced4da; }
    .form-control:focus { border-color: #0C1E44; box-shadow: 0 0 0 0.25 margin-bottom: 5px; }
    .input-group-text { border-radius: 8px 0 0 8px; border-right: none; }
    .input-group > .form-control { border-radius: 0 8px 8px 0; }
    .btn-outline-danger { border-radius: 0 8px 8px 0; }
</style>
<?php $this->endSection(); ?>