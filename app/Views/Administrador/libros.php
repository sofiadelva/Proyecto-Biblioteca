<?php 
// Extiende de la plantilla principal llamada "plantilla_admin"
echo $this->extend('Plantillas/plantilla_admin'); 
?>

<?php 
// Define la sección "titulo" de la plantilla
$this->section('titulo'); 
?>
Libros
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido" que se mostrará en el layout
$this->section('contenido'); 
?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="<?= base_url('libros/new'); ?>" class="btn btn-lg text-white shadow" style="background-color:#0C1E44;">
        <i class="bi bi-plus-circle-fill me-2"></i>Agregar Nuevo Libro
    </a>
    
    <form method="get" action="<?= base_url('libros'); ?>" class="search-bar-container">
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar por Código, Título o Autor..." 
            value="<?= esc($buscar ?? '') ?>" 
        />
        <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
        <input type="hidden" name="cantidad_disponible" value="<?= esc($_GET['cantidad_disponible'] ?? '') ?>">
        <input type="hidden" name="per_page" value="<?= esc($_GET['per_page'] ?? '') ?>">

        <button type="submit" class="search-icon">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>
<div class="row mb-3">
    
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-secondary border-opacity-25">
            <div class="card-body py-3">
                <h6 class="card-title text-muted mb-3"><i class="bi bi-sort-alpha-down me-2"></i>Opciones de Visualización</h6>
                
                <form class="d-flex align-items-center mb-3" method="get" action="<?= base_url('libros'); ?>">
                    <input type="number" name="per_page" value="<?= $perPage ?? 10 ?>" min="1" class="form-control w-auto me-2" style="max-width: 150px;" placeholder="Filas">
                    
                    
                    <select name="ordenar" class="form-select w-auto me-2">
                        <option value="">Ordenar por...</option>
                        <option value="titulo_asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'titulo_asc') ? 'selected' : '' ?>>Título A → Z</option>
                        <option value="titulo_desc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'titulo_desc') ? 'selected' : '' ?>>Título Z → A</option>
                        <option value="autor_asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'autor_asc') ? 'selected' : '' ?>>Autor A → Z</option>
                        <option value="autor_desc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'autor_desc') ? 'selected' : '' ?>>Autor Z → A</option>
                        <option value="reciente" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'reciente') ? 'selected' : '' ?>>Más reciente</option>
                        <option value="viejo" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'viejo') ? 'selected' : '' ?>>Más antiguo</option>
                    </select>
                    <div class="d-flex justify-content-end mt-3">
                        <a href="<?= base_url('libros') ?>" class="btn btn-outline-secondary btn-sm me-2">Limpiar</a>
                        <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Aplicar Filtros</button>
                    </div>

                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                    <input type="hidden" name="estado" value="<?= esc($_GET['estado'] ?? '') ?>">
                    <input type="hidden" name="cantidad_disponible" value="<?= esc($_GET['cantidad_disponible'] ?? '') ?>">
                    
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-secondary border-opacity-25">
            <div class="card-body py-3">
                <h6 class="card-title text-muted mb-3"><i class="bi bi-funnel-fill me-2"></i>Opciones de Filtrado</h6>
                <form id="filterForm" method="get" action="<?= base_url('libros'); ?>">
                    <div class="row g-2">
                        <div class="col-4">
                            <select name="cantidad_disponible" class="form-select">
                                <option value="">Cantidad...</option>
                                <option value="0" <?= (isset($_GET['cantidad_disponible']) && $_GET['cantidad_disponible'] == '0') ? 'selected' : '' ?>>0 disp.</option>
                                <option value="1" <?= (isset($_GET['cantidad_disponible']) && $_GET['cantidad_disponible'] == '1') ? 'selected' : '' ?>>1 o más</option>
                            </select>
                        </div>
                        
                        <div class="col-4">
                            <select name="coleccion_id" id="filter_coleccion" class="form-select select2-ajax" style="width: 100% !important;">
                                <?php if(!empty($coleccion_id_sel)): ?>
                                    <option value="<?= $coleccion_id_sel ?>" selected>Cargando...</option>
                                <?php else: ?>
                                    <option value="">Colección...</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-4">
                            <select name="subgenero_id" id="filter_subgenero" class="form-select select2-ajax" style="width: 100%;" <?= empty($coleccion_id_sel) ? 'disabled' : '' ?>>
                                <?php if(!empty($subgenero_id_sel)): ?>
                                    <option value="<?= $subgenero_id_sel ?>" selected>Cargando...</option>
                                <?php else: ?>
                                    <option value="">Subgénero...</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="<?= base_url('libros') ?>" class="btn btn-outline-secondary btn-sm me-2">Limpiar</a>
                        <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Aplicar Filtros</button>
                    </div>

                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                    <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
                    <input type="hidden" name="per_page" value="<?= esc($_GET['per_page'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>
</div>

<table class="table clean-table my-3">
    <thead>
    <tr>
        <th>#</th>
        <th>Código</th> 
        <th>Título</th>
        <th>Autor</th>
        <th>Editorial</th>
        <th>Año</th>
        <th>Páginas</th>
        <th>Colección</th> 
        <th>Subgénero</th>
        <th>Subcategoría</th>
        <th>Cantidad Total</th>
        <th>Cantidad Disponibles</th>
        <th>Opciones</th>
    </tr>
    </thead>
    <tbody>
    <?php 
    // Intentamos obtener 'page_default', si no existe probamos con 'page', y si no, es la 1
     $request = \Config\Services::request();
    $paginaActual = $request->getVar('page_default') ?? $request->getVar('page') ?? 1;
    $paginaActual = (int)$paginaActual;
    $porPagina = $perPage ?? 10; 
 $contador = ($paginaActual - 1) * $porPagina + 1;
foreach($libros as $libro): 
?>
        <tr>
            <td class="fw-bold text-muted"><?= $contador++ ?></td>
            <td><?= esc($libro['codigo']) ?></td> 
            <td><?= esc($libro['titulo']) ?></td>
            <td><?= esc($libro['autor']) ?></td>
            <td><?= esc($libro['editorial']) ?></td>
            <td><?= esc($libro['ano']) ?></td> 
            <td><?= esc($libro['paginas']) ?></td> 
            
            <td><?= esc($libro['coleccion_nombre'] ?? 'N/A') ?></td> 
            <td><?= esc($libro['subgenero_nombre'] ?? 'N/A') ?></td> 
            <td><?= esc($libro['subcategoria_nombre'] ?? '') ?></td> 
            
            <td><?= esc($libro['cantidad_total']) ?></td>
            <td><?= esc($libro['cantidad_disponibles']) ?></td>
            <td>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('libros/edit/'.$libro['libro_id']); ?>" 
                        class="btn-sm btn-accion-editar">Editar</a>

                    <a href="<?= base_url('libros/delete/'.$libro['libro_id']); ?>" 
                        class="btn-sm btn-accion-eliminar"
                        onclick="return confirm('¿Seguro que quieres eliminar este libro?')">Eliminar</a>

                    <a href="<?= base_url('ejemplares/listar/'.$libro['libro_id']); ?>" 
                        class="btn btn-sm btn-info"
                        title="Ver Ejemplares"
                        style="background-color:#206060; color:#fff; border-color:#206060;">
                        <i class="bi bi-list-columns-reverse"></i>
                    </a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="mt-4">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
    // 1. Inicializar Select2 para Colección
    $('#filter_coleccion').select2({
        theme: 'bootstrap-5',
        placeholder: 'Colección...',
        allowClear: true,
        ajax: {
            url: '<?= base_url("libros/get_colecciones_json") ?>',
            dataType: 'json',
            delay: 250,
            processResults: function(data) { return { results: data.results }; }
        }
    });

    // 2. Inicializar Select2 para Subgénero
    $('#filter_subgenero').select2({
        theme: 'bootstrap-5',
        placeholder: 'Subgénero...',
        allowClear: true,
        ajax: {
            url: '<?= base_url("libros/get_subgeneros_json") ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    term: params.term,
                    coleccion_id: $('#filter_coleccion').val() // Filtra por la colección elegida
                };
            },
            processResults: function(data) { return { results: data.results }; }
        }
    });

    // Lógica de cascada: Si cambia colección, resetear subgénero
    $('#filter_coleccion').on('change', function() {
        if ($(this).val()) {
            $('#filter_subgenero').prop('disabled', false).val(null).trigger('change');
        } else {
            $('#filter_subgenero').prop('disabled', true).val(null).trigger('change');
        }
    });

    // Lógica para precargar nombres si hay filtros activos (opcional pero recomendado)
    <?php if(!empty($coleccion_id_sel)): ?>
        $.ajax({
            url: '<?= base_url("libros/get_colecciones_json") ?>',
            data: { id: '<?= $coleccion_id_sel ?>' }
        }).then(function(data) {
            if(data.results.length > 0) {
                var option = new Option(data.results[0].text, data.results[0].id, true, true);
                $('#filter_coleccion').append(option).trigger('change.select2'); // Usamos change.select2 para no disparar el reset
            }
        });
    <?php endif; ?>

    // 🌟 NUEVO: Lógica para precargar el nombre del Subgénero seleccionado
    <?php if(!empty($subgenero_id_sel)): ?>
        $.ajax({
            url: '<?= base_url("libros/get_subgeneros_json") ?>',
            data: { id: '<?= $subgenero_id_sel ?>' }
        }).then(function(data) {
            if(data.results.length > 0) {
                var option = new Option(data.results[0].text, data.results[0].id, true, true);
                $('#filter_subgenero').append(option).trigger('change.select2');
            }
        });
    <?php endif; ?>
});
</script>
<?php 
$this->endSection(); 
?>