<?php echo $this->extend('Plantillas/plantilla_admin'); ?> 
<?php $this->section('titulo'); ?>
Editar Transacción #<?= esc($transaccion['prestamo_id']) ?>
<?php $this->endSection(); ?> 
<?php $this->section('contenido'); ?> 
<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    
    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-pencil-square me-2" style="color: #0C1E44;"></i>
        Editar Préstamo #<?= esc($transaccion['prestamo_id']) ?>
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

    <?php if(session()->getFlashdata('msg')): ?>
        <div class="alert alert-warning">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('transacciones/update/'.$transaccion['prestamo_id']); ?>" method="post" class="row g-4" autocomplete="off">
        
        <input type="hidden" name="prestamo_id" value="<?= esc($transaccion['prestamo_id']) ?>">
        
        <div class="col-12 mt-4">
            <h5 class="fw-bold text-secondary pb-1 border-bottom border-light">Datos del Prestatario</h5>
        </div>

        <div class="col-md-6">
            <label for="usuario_select" class="form-label fw-bold">Usuario <span class="text-danger">*</span></label>
            <select class="form-select" name="usuario_id" id="usuario_select" required>
                <option value="<?= esc($transaccion['usuario_id']) ?>" selected>
                    Cargando Usuario...
                </option>
            </select>
            <small class="form-text text-muted">Busque al usuario por carné o nombre.</small>
        </div>

        <div class="col-12 mt-5">
            <h5 class="fw-bold text-secondary pb-1 border-bottom border-light">Datos del Libro y Ejemplar</h5>
        </div>

        <div class="col-md-6">
            <label for="libro_select" class="form-label fw-bold">Libro <span class="text-danger">*</span></label>
            <select class="form-select" name="libro_id" id="libro_select" required>
                 <option value="<?= esc($transaccion['libro_id']) ?>" selected>
                    Cargando Libro...
                </option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="ejemplar_id" class="form-label fw-bold">Ejemplar (ID Inventario) <span class="text-danger">*</span></label>
            <select class="form-select" name="ejemplar_id" id="ejemplar_id" required disabled>
                <option value="<?= esc($transaccion['ejemplar_id']) ?>" selected>
                    Cargando Ejemplar Actual...
                </option>
            </select>
            <small class="form-text text-muted">Se listarán los ejemplares disponibles y el actualmente prestado.</small>
        </div>
        
        <div class="col-12 mt-5">
            <h5 class="fw-bold text-secondary pb-1 border-bottom border-light">Fechas y Estado</h5>
        </div>

        <div class="col-md-4">
            <label for="fecha_prestamo" class="form-label fw-bold">Fecha de Préstamo <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="fecha_prestamo" value="<?= esc($transaccion['fecha_prestamo']) ?>" required>
        </div>

        <div class="col-md-4">
            <label for="fecha_de_devolucion" class="form-label fw-bold">Fecha Límite Devolución <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="fecha_de_devolucion" value="<?= esc($transaccion['fecha_de_devolucion']) ?>" required>
        </div>

        <div class="col-md-4">
            <label for="fecha_devuelto" class="form-label fw-bold">Fecha Devuelto (Real)</label>
            <input type="date" class="form-control" name="fecha_devuelto" value="<?= esc($transaccion['fecha_devuelto']) ?>">
            <small class="form-text text-muted">Dejar en blanco si no se ha devuelto aún.</small>
        </div>

        <div class="col-md-6">
            <label for="estado" class="form-label fw-bold">Estado del Préstamo <span class="text-danger">*</span></label>
            <select name="estado" id="estado" class="form-select" required>
                <?php $estados = ['En proceso', 'Devuelto']; // ¡VENCIDO ELIMINADO! ?>
                <?php foreach($estados as $e): ?>
                    <option value="<?= $e ?>" <?= $e == $transaccion['estado'] ? 'selected' : '' ?>>
                        <?= $e ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="form-text text-muted">Cambiar a **Devuelto** para liberar el ejemplar y marcar la transacción como completada.</small>
        </div>

        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <a href="<?= base_url('transacciones'); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
                <i class="bi bi-arrow-left-short"></i> Regresar
            </a>
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#A01E53;">
                <i class="bi bi-save-fill me-2"></i> Guardar Cambios
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
    .form-control, .form-select, .select2-container--bootstrap4 .select2-selection--single {
        border-radius: 8px;
        padding: 10px 15px !important; 
        box-shadow: none !important;
        border: 1px solid #ced4da;
        height: auto !important;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        padding-left: 0 !important;
        line-height: inherit !important;
    }
    .select2-container .select2-selection--single {
        height: 44px !important;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
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

<?php 
// SECCIÓN DE SCRIPTS: Lógica AJAX para Select2 y ejemplares
$this->section('scripts'); 
?>
<script>
    $(document).ready(function() {
        const libroSelect = $('#libro_select');
        const ejemplarSelect = document.getElementById('ejemplar_id');
        const usuarioSelect = $('#usuario_select');
        
        const currentEjemplarId = '<?= esc($transaccion['ejemplar_id']) ?>';
        const currentLibroId = '<?= esc($transaccion['libro_id']) ?>';
        const currentUsuarioId = '<?= esc($transaccion['usuario_id']) ?>';

        // Función para cargar ejemplares
        function cargarEjemplares(libroId, selectedEjemplarId = null) {
            ejemplarSelect.innerHTML = '<option value="">Cargando ejemplares...</option>';
            ejemplarSelect.disabled = true;

            if (!libroId) {
                ejemplarSelect.innerHTML = '<option value="">Seleccione un libro primero</option>';
                return;
            }

            // Usamos la ruta de préstamos que obtiene ejemplares disponibles + el ejemplar actual si es el caso
            fetch(`<?= base_url('prestamos/getEjemplares') ?>/${libroId}`) 
                .then(response => response.json())
                .then(data => {
                    ejemplarSelect.innerHTML = '';
                    if (data.length > 0) {
                        ejemplarSelect.disabled = false;
                        ejemplarSelect.innerHTML += '<option value="">Seleccione un ejemplar</option>';
                        
                        let ejemplarFound = false;

                        data.forEach(ejemplar => {
                            const option = document.createElement('option');
                            option.value = ejemplar.ejemplar_id;
                            
                            // Mostrar no_copia
                            option.textContent = `ID Inventario: ${ejemplar.ejemplar_id} (No. Copia: ${ejemplar.no_copia ?? 'N/A'})`;
                            
                            // Seleccionar el ejemplar actual si coincide
                            if (selectedEjemplarId && ejemplar.ejemplar_id == selectedEjemplarId) {
                                option.selected = true;
                                ejemplarFound = true;
                            }

                            ejemplarSelect.appendChild(option);
                        });
                        
                        // Si el ejemplar actual NO estaba en la lista de disponibles (ej. está prestado)
                        // Aseguramos que se añada la opción para que se muestre como seleccionado.
                        if (selectedEjemplarId && !ejemplarFound) {
                             // Simulamos la carga del ejemplar actual (solo para display)
                             const option = document.createElement('option');
                             option.value = selectedEjemplarId;
                             option.textContent = `ID Inventario: ${selectedEjemplarId} (Actualmente prestado - Debe liberarse)`;
                             option.selected = true;
                             ejemplarSelect.prepend(option);
                        }


                    } else {
                        ejemplarSelect.innerHTML = '<option value="">No hay ejemplares disponibles</option>';
                        // Si solo está el ejemplar actual, lo ponemos como única opción
                        if (selectedEjemplarId) {
                            const option = document.createElement('option');
                            option.value = selectedEjemplarId;
                            option.textContent = `ID Inventario: ${selectedEjemplarId} (Ejemplar en uso)`;
                            option.selected = true;
                            ejemplarSelect.appendChild(option);
                            ejemplarSelect.disabled = false;
                        } else {
                            ejemplarSelect.disabled = true;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error al obtener ejemplares:', error);
                    ejemplarSelect.innerHTML = '<option value="">Error al cargar ejemplares</option>';
                    ejemplarSelect.disabled = true;
                });
        }

        // Event listener para el cambio de libro (Select2)
        libroSelect.on('change', function() {
            // Cuando cambia el libro, reseteamos el ejemplar a NULL
            cargarEjemplares(this.value, null); 
        });


        // --- 1. Inicialización de Select2 para Usuario ---
        usuarioSelect.select2({
            placeholder: "Buscar usuario por carné o nombre",
            allowClear: true,
            theme: "bootstrap4", 
            minimumInputLength: 2,
            ajax: {
                url: '<?= base_url('usuarios/getUsuariosJson'); ?>', 
                dataType: 'json',
                delay: 250, 
                data: function (params) { return { term: params.term }; },
                processResults: function (data) { return { results: data.results }; },
                cache: true
            }
        });
        
        // --- 2. Inicialización de Select2 para Libro ---
        libroSelect.select2({
            placeholder: "Buscar libro por título o autor",
            allowClear: true,
            theme: "bootstrap4", 
            minimumInputLength: 2,
            ajax: {
                url: '<?= base_url('prestamos/getLibrosJson'); ?>', 
                dataType: 'json',
                delay: 250, 
                data: function (params) { return { term: params.term }; },
                processResults: function (data) { return { results: data.results }; },
                cache: true
            }
        });
        
        // --- 3. Recarga de valores iniciales (al cargar la página) ---
        
        // 3.1. Recargar Usuario 
        $.ajax({
            dataType: 'json',
            url: '<?= base_url('usuarios/getUsuariosJson'); ?>',
            data: { id: currentUsuarioId } 
        }).then(function (data) {
            var usuario = data.results[0]; 
            if (usuario) {
                var newOption = new Option(usuario.text, usuario.id, true, true);
                usuarioSelect.append(newOption).trigger('change.select2');
            }
        });
        
        // 3.2. Recargar Libro
         $.ajax({
            dataType: 'json',
            url: '<?= base_url('prestamos/getLibrosJson'); ?>',
            data: { id: currentLibroId } 
        }).then(function (data) {
            var libro = data.results[0]; 
            if (libro) {
                var newOption = new Option(libro.text, libro.id, true, true);
                libroSelect.append(newOption).trigger('change.select2');
            }
            // Cargar los ejemplares, asegurando que se seleccione el ejemplar actual.
            cargarEjemplares(currentLibroId, currentEjemplarId);
        });
    });
</script>
<?php 
$this->endSection(); 
?>