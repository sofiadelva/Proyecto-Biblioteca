<?php 
// Extiende de la plantilla principal del administrador
echo $this->extend('Plantillas/plantilla_admin'); 
?>

<?php 
// Define la sección "titulo"
$this->section('titulo'); 
?>
Registrar Transacción (Préstamo)
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido"
$this->section('contenido'); 
?>

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    
    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-arrow-right-circle-fill me-2" style="color: #206060;"></i>
        Registrar Nuevo Préstamo
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

    <form action="<?= base_url('transacciones/store'); ?>" method="post" class="row g-4" autocomplete="off">
        
        <div class="col-12">
            <h5 class="fw-bold text-secondary pb-1 border-bottom border-light">Datos del Prestatario</h5>
        </div>

        <div class="col-md-6">
            <label for="usuario_select" class="form-label fw-bold">Carné del Usuario <span class="text-danger">*</span></label>
            <select class="form-select" name="carne" id="usuario_select" required>
                <option value="<?= old('carne') ?>"> 
                    <?= old('carne') ? 'Cargando Usuario: ' . esc(old('carne')) : 'Buscar usuario por carné o nombre' ?>
                </option>
            </select>
            <small class="form-text text-muted">Busque al usuario por carné o nombre.</small>
        </div>
        
        <div class="col-12 mt-5">
            <h5 class="fw-bold text-secondary pb-1 border-bottom border-light">Datos del Libro</h5>
        </div>

        <div class="col-md-6">
            <label for="libro_select" class="form-label fw-bold">Libro a Prestar <span class="text-danger">*</span></label>
            <select class="form-select" name="libro_id" id="libro_select" required>
                 <option value="<?= old('libro_id') ?>">
                    <?= old('libro_id') ? 'Cargando Libro (ID: ' . esc(old('libro_id')) . ')' : 'Buscar libro por título o autor' ?>
                </option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="ejemplar_id" class="form-label fw-bold">Ejemplar (ID de Inventario) <span class="text-danger">*</span></label>
            <select class="form-select" name="ejemplar_id" id="ejemplar_id" required disabled>
                <option value="">Seleccione un libro primero</option>
                <?php if(old('ejemplar_id')): ?>
                    <option value="<?= esc(old('ejemplar_id')) ?>" selected>Cargando ejemplar...</option>
                <?php endif; ?>
            </select>
            <small class="form-text text-muted">Se listarán solo los ejemplares disponibles del libro seleccionado.</small>
        </div>
        
        <div class="col-12 mt-5">
            <h5 class="fw-bold text-secondary pb-1 border-bottom border-light">Fechas del Préstamo</h5>
        </div>

        <div class="col-md-6">
            <label for="fecha_prestamo" class="form-label fw-bold">Fecha de Préstamo <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="fecha_prestamo" value="<?= old('fecha_prestamo') ?? date('Y-m-d') ?>" required>
        </div>

        <div class="col-md-6">
            <label for="fecha_de_devolucion" class="form-label fw-bold">Fecha Límite de Devolución <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="fecha_de_devolucion" value="<?= old('fecha_de_devolucion') ?? date('Y-m-d', strtotime('+7 days')) ?>" required>
        </div>
        
        <input type="hidden" name="fecha_devuelto" value="">
        <input type="hidden" name="estado" value="En proceso">


        <div class="col-12 mt-5 d-flex justify-content-start gap-3">
            <a href="<?= base_url('transacciones'); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
                <i class="bi bi-arrow-left-short"></i> Regresar
            </a>
            <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#206060;">
                <i class="bi bi-plus-circle-fill me-2"></i> Confirmar Préstamo
            </button>
        </div>

    </form>
</div>

<style>
    /* Incluimos los estilos necesarios */
    .section-title {
        color: #206060;
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

<?php 
$this->endSection(); 
?>

<?php 
// SECCIÓN DE SCRIPTS: Lógica AJAX para Select2 y ejemplares
$this->section('scripts'); 
?>
<script>
    $(document).ready(function() {
        const libroSelect = $('#libro_select');
        const ejemplarSelect = document.getElementById('ejemplar_id');
        const usuarioSelect = $('#usuario_select');
        
        // Función para cargar ejemplares
        function cargarEjemplares(libroId, oldEjemplarId = null) {
            ejemplarSelect.innerHTML = '<option value="">Cargando ejemplares...</option>';
            ejemplarSelect.disabled = true;

            if (!libroId) {
                ejemplarSelect.innerHTML = '<option value="">Seleccione un libro primero</option>';
                return;
            }

            // Nota: La URL para obtener ejemplares debe ser definida en tu controlador o usar una ruta compartida
            // Asumo que tienes una ruta para esto, si no, tendrás que definirla en routes.php (ej. 'ajax/getEjemplares/...')
            fetch(`<?= base_url('prestamos/getEjemplares') ?>/${libroId}`) 
                .then(response => response.json())
                .then(data => {
                    ejemplarSelect.innerHTML = '';
                    if (data.length > 0) {
                        ejemplarSelect.disabled = false;
                        ejemplarSelect.innerHTML += '<option value="">Seleccione un ejemplar</option>';
                        data.forEach(ejemplar => {
                            const option = document.createElement('option');
                            option.value = ejemplar.ejemplar_id;
                            
                            // Mostrar no_copia
                            option.textContent = `ID Inventario: ${ejemplar.ejemplar_id} (No. Copia: ${ejemplar.no_copia ?? 'N/A'})`;
                            
                            if (oldEjemplarId && ejemplar.ejemplar_id == oldEjemplarId) {
                                option.selected = true;
                            }

                            ejemplarSelect.appendChild(option);
                        });

                    } else {
                        ejemplarSelect.innerHTML = '<option value="">No hay ejemplares disponibles</option>';
                        ejemplarSelect.disabled = true;
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
            cargarEjemplares(this.value); 
        });


        // --- 1. Inicialización de Select2 para Usuario (Carné) ---
        usuarioSelect.select2({
            placeholder: "Buscar usuario por carné o nombre",
            allowClear: true,
            theme: "bootstrap4", 
            minimumInputLength: 2,
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
        
        // --- 2. Inicialización de Select2 para Libro (ID) ---
        libroSelect.select2({
            placeholder: "Buscar libro por título o autor",
            allowClear: true,
            theme: "bootstrap4", 
            minimumInputLength: 2,
            ajax: {
                // Nota: La URL para obtener libros también se comparte con el controlador Prestamos
                url: '<?= base_url('prestamos/getLibrosJson'); ?>', 
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
        
        // --- 3. Recarga de valores 'old' (manejo de errores de validación) ---
        
        // 3.1. Recargar Usuario (Carné)
        const initialCarne = '<?= old('carne') ?>';
        if (initialCarne) {
            console.log("Recargando valor old del usuario con carné:", initialCarne);
            $.ajax({
                dataType: 'json',
                url: '<?= base_url('usuarios/getUsuariosJson'); ?>',
                data: { id: initialCarne } 
            }).then(function (data) {
                var usuario = data.results[0]; 
                if (usuario) {
                    var newOption = new Option(usuario.text, usuario.id, true, true);
                    usuarioSelect.append(newOption).trigger('change.select2');
                }
            });
        }
        
        // 3.2. Recargar Libro y Ejemplares
        const initialLibroId = '<?= old('libro_id') ?>';
        const initialEjemplarId = '<?= old('ejemplar_id') ?>';
        
        if (initialLibroId) {
            console.log("Recargando valor old del libro con ID:", initialLibroId);
             $.ajax({
                dataType: 'json',
                url: '<?= base_url('prestamos/getLibrosJson'); ?>',
                data: { id: initialLibroId } 
            }).then(function (data) {
                var libro = data.results[0]; 
                if (libro) {
                    var newOption = new Option(libro.text, libro.id, true, true);
                    libroSelect.append(newOption).trigger('change.select2');
                }
                // Cargar los ejemplares con el ID de ejemplar antiguo
                cargarEjemplares(initialLibroId, initialEjemplarId);
            });
        }
    });
</script>
<?php 
$this->endSection(); 
?>