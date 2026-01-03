<?php echo $this->extend('Plantillas/plantilla_admin'); ?> 
<?php $this->section('titulo'); ?>
Editar Transacción
<?php $this->endSection(); ?> 
<?php $this->section('contenido'); ?> 
<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    
    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-pencil-square me-2" style="color: #0C1E44;"></i>
        Editar Préstamo 
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

    <form action="<?= base_url('transacciones/update/'.$transaccion['prestamo_id']); ?>" method="post" class="row g-4" id="form-editar-transaccion">
    
        <input type="hidden" name="prestamo_id" value="<?= esc($transaccion['prestamo_id']) ?>">
        
        <div class="col-md-12">
            <label class="form-label fw-bold text-muted">Libro y Ejemplar (No Editable)</label>
            <input type="text" class="form-control input-bloqueado" value="<?= esc($transaccion['codigo']) ?> - <?= esc($transaccion['titulo']) ?> (Copia: <?= esc($transaccion['no_copia']) ?>)" readonly>
            <input type="hidden" name="libro_id" value="<?= $transaccion['libro_id'] ?>">
            <input type="hidden" name="ejemplar_id" value="<?= $transaccion['ejemplar_id'] ?>">
        </div>

        <div class="col-md-6">
            <label for="usuario_select" class="form-label fw-bold">Usuario / Prestatario <span class="text-danger">*</span></label>
                <select class="form-select" name="usuario_id" id="usuario_select" required>
                    <option value="<?= $transaccion['usuario_id'] ?>" selected>
                        <?= esc($transaccion['usuario_nombre']) ?>
                    </option>
                </select>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Estado</label>
            <?php if ($transaccion['estado'] == 'Devuelto'): ?>
                <select name="estado" id="estado_select" class="form-select border-warning">
                    <option value="Devuelto" <?= ($transaccion['estado'] == 'Devuelto') ? 'selected' : '' ?>>Devuelto</option>
                    <option value="En proceso" <?= ($transaccion['estado'] == 'En proceso') ? 'selected' : '' ?>>Revertir a: En proceso</option>
                </select>
            <?php else: ?>
                <input type="text" class="form-control input-bloqueado" value="En proceso" readonly>
                <input type="hidden" name="estado" value="En proceso">
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">Fecha Préstamo</label>
            <input type="date" class="form-control" name="fecha_prestamo" value="<?= esc($transaccion['fecha_prestamo']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Fecha Límite</label>
            <input type="date" class="form-control" name="fecha_de_devolucion" value="<?= esc($transaccion['fecha_de_devolucion']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Fecha Real Devuelto</label>
            <input type="date" class="form-control" name="fecha_devuelto" id="fecha_devuelto_input" 
                value="<?= esc($transaccion['fecha_devuelto']) ?>" 
                <?= ($transaccion['estado'] != 'Devuelto') ? 'readonly' : '' ?>>
            <small id="aviso_fecha" class="text-muted" style="<?= ($transaccion['estado'] != 'Devuelto') ? '' : 'display:none;' ?>">
                No editable en proceso.
            </small>
        </div>

        <div class="col-12 mt-4 d-flex gap-3">
            <a href="<?= base_url('transacciones'); ?>" class="btn btn-secondary px-4">Cancelar</a>
            <button type="submit" class="btn text-white px-4" style="background-color:#A01E53;">Guardar Cambios</button>
        </div>
    </form>


</div>

<style>

    /* Forzar color gris en bloqueados y quitar el amarillo de Chrome */
    .input-bloqueado, 
    .form-control[readonly] {
        background-color: #e9ecef !important; /* Gris claro de Bootstrap */
        color: #6c757d !important;
        cursor: not-allowed;
    }

    /* Quita el fondo amarillo de autocompletado */
    input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px #e9ecef inset !important;
        -webkit-text-fill-color: #6c757d !important;
    }

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

<?php $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        const usuarioSelect = $('#usuario_select');
        const estadoSelect = $('#estado_select'); 
        const fechaDevueltoInput = $('#fecha_devuelto_input');
        const avisoFecha = $('#aviso_fecha');
        const currentUsuarioId = '<?= esc($transaccion['usuario_id']) ?>';

        // 1. Inicialización de Select2 para búsqueda de usuarios
        usuarioSelect.select2({
            placeholder: "Buscar usuario...",
            theme: "bootstrap4",
            minimumInputLength: 2,
            allowClear: true,
            ajax: {
                url: '<?= base_url('usuarios/getUsuariosJson'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) { 
                    return { term: params.term }; 
                },
                processResults: function (data) { 
                    return { results: data.results }; 
                },
                cache: true
            }
        });

        // Cargar los datos del usuario actual en el Select2 (Pre-selección)
        if (currentUsuarioId && currentUsuarioId !== '0') {
            $.ajax({
                dataType: 'json',
                url: '<?= base_url('usuarios/getUsuariosJson'); ?>',
                data: { id: currentUsuarioId }
            }).then(function (data) {
                if (data.results && data.results.length > 0) {
                    var usuario = data.results[0];
                    var newOption = new Option(usuario.text, usuario.id, true, true);
                    usuarioSelect.append(newOption).trigger('change');
                }
            });
        }

        // 2. Lógica de control de interfaz (Estado y Fecha)
        function aplicarLogicaProceso() {
            // trim() para evitar errores por espacios o mayúsculas
            const estadoActual = estadoSelect.val() ? estadoSelect.val().trim() : '';
            
            if (estadoActual === 'En proceso') {
                // Bloqueamos la fecha de devolución real porque el libro vuelve a estar prestado
                fechaDevueltoInput.val(''); 
                fechaDevueltoInput.attr('readonly', true);
                fechaDevueltoInput.addClass('input-bloqueado');
                fechaDevueltoInput.attr('placeholder', 'N/A'); 
                
                // Mensaje informativo dinámico
                avisoFecha.html('<i class="bi bi-exclamation-triangle-fill"></i> Al revertir a <b>En proceso</b>, se restará 1 unidad del inventario.').fadeIn();
                avisoFecha.removeClass('text-muted').addClass('text-danger fw-bold');
            } else {
                // Si vuelve a "Devuelto", habilitamos la fecha
                fechaDevueltoInput.attr('readonly', false);
                fechaDevueltoInput.removeClass('input-bloqueado');
                fechaDevueltoInput.attr('placeholder', '');
                avisoFecha.fadeOut();
            }
        }

        // Ejecutar la validación al cargar la página y cada vez que cambie el select
        if (estadoSelect.length) {
            estadoSelect.on('change', aplicarLogicaProceso);
            aplicarLogicaProceso();
        }

        // 3. VALIDACIÓN FINAL ANTES DE ENVIAR (Previene Error 1452)
        $('#form-editar-transaccion').on('submit', function(e) {
            const selectedUsuario = usuarioSelect.val();

            // Si el ID del usuario está vacío o es nulo, detenemos el envío
            if (!selectedUsuario || selectedUsuario === "" || selectedUsuario === "0") {
                e.preventDefault();
                alert("Error: Debes seleccionar un usuario válido para guardar los cambios.");
                usuarioSelect.select2('open'); // Abre el buscador para que el usuario elija
                return false;
            }

            // Si el estado es "En proceso", nos aseguramos de que la fecha viaje vacía
            if (estadoSelect.val() === 'En proceso') {
                fechaDevueltoInput.val(''); 
            }
            
            return true;
        });
    });
</script>
<?php $this->endSection(); ?>