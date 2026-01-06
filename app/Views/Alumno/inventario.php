<?php echo $this->extend('Plantillas/plantilla_alumno'); ?>

<?php $this->section('titulo'); ?>Libros<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-muted"><i class="bi bi-book me-2"></i>Catálogo de Libros</h4>
    
    <form method="get" action="<?= base_url('alumno/inventario'); ?>" class="search-bar-container">
        <input type="text" name="buscar" placeholder="Buscar por Título o Autor..." value="<?= esc($buscar ?? '') ?>" />
        <button type="submit" class="search-icon"><i class="bi bi-search"></i></button>
    </form>
</div>

<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body">
        <form id="filterForm" method="get" action="<?= base_url('alumno/inventario'); ?>">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Colección</label>
                    <select name="coleccion_id" id="filter_coleccion" class="form-select select2-ajax">
                        <option value="">Todas las Colecciones</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Subgénero</label>
                    <select name="subgenero_id" id="filter_subgenero" class="form-select select2-ajax" <?= empty($coleccion_id_sel) ? 'disabled' : '' ?>>
                        <option value="">Todos los Subgéneros</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel-fill me-2"></i>Filtrar
                    </button>
                    <a href="<?= base_url('alumno/inventario') ?>" class="btn btn-outline-secondary">Limpiar</a>
                </div>
            </div>
            <input type="hidden" name="buscar" value="<?= esc($buscar ?? '') ?>">
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="clean-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Editorial</th>
                <th>Año</th>
                <th>Colección</th>
                <th>Subgénero</th>
                <th>Subcategoría</th>
                <th class="text-center">Disponibilidad</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($libros)): ?>
            <?php 
                // Usamos la función de ayuda request() para obtener la página actual
                $page    = (int)(request()->getGet('page') ?? 1);
                $perPage = 10;
                $i       = ($page <= 1) ? 1 : (($page - 1) * $perPage + 1); 
            ?>
            <?php foreach($libros as $libro): ?>
                <tr>
                    <td class="text-muted fw-bold"><?= $i++ ?></td>
                    <td class="fw-bold text-dark"><?= esc($libro['titulo']) ?></td>
                    <td><?= esc($libro['autor']) ?></td>
                    <td><?= esc($libro['editorial']) ?></td>
                    <td><?= esc($libro['ano']) ?></td>
                    <td><span><?= esc($libro['coleccion_nombre'] ?? 'N/A') ?></span></td>
                    <td ><?= esc($libro['subgenero_nombre'] ?? 'N/A') ?></td>
                    <td class="small text-muted italic"><?= esc($libro['subcategoria_nombre'] ?? '') ?></td>
                    <td class="text-center">
                        <?php if ($libro['cantidad_disponibles'] >= 1): ?>
                            <span class="badge bg-success rounded-pill px-3">Disponible</span>
                        <?php else: ?>
                            <span class="badge bg-danger rounded-pill px-3">No disponible</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center py-5 text-muted">
                    <i class="bi bi-info-circle me-2"></i>No se encontraron resultados para tu búsqueda.
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>



<div class="mt-4 d-flex justify-content-center">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>
<div class="footer-credits">
                Página realizada por: Sofía del Valle Ajosal y Emily Abril Santizo Urízar - Promo 2025
            </div>

<style>
    .footer-credits {
        margin-top: auto; 
        padding-top: 20px;
        padding-bottom: 5px;
        text-align: center;
        font-size: 0.85rem;
        color: #6c757d; 
        border-top: 1px solid #e9ecef; 
    }
</style>
<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Configuración Select2 con tema Bootstrap 4 (el de tu plantilla)
    $('#filter_coleccion').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione Colección',
        ajax: {
            url: '<?= base_url("libros/get_colecciones_json") ?>',
            dataType: 'json',
            processResults: function(data) { return { results: data.results }; }
        }
    });

    $('#filter_subgenero').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione Subgénero',
        ajax: {
            url: '<?= base_url("libros/get_subgeneros_json") ?>',
            dataType: 'json',
            data: function(params) {
                return { term: params.term, coleccion_id: $('#filter_coleccion').val() };
            },
            processResults: function(data) { return { results: data.results }; }
        }
    });

    // Lógica de cascada
    $('#filter_coleccion').on('change', function() {
        if ($(this).val()) {
            $('#filter_subgenero').prop('disabled', false).val(null).trigger('change');
        } else {
            $('#filter_subgenero').prop('disabled', true).val(null).trigger('change');
        }
    });

    // Precargar valores si existen
    <?php if(!empty($coleccion_id_sel)): ?>
        $.ajax({ 
            url: '<?= base_url("libros/get_colecciones_json") ?>', 
            data: { id: '<?= $coleccion_id_sel ?>' } 
        }).then(function(data) {
            if(data.results.length > 0) {
                var option = new Option(data.results[0].text, data.results[0].id, true, true);
                $('#filter_coleccion').append(option).trigger('change.select2');
            }
        });
    <?php endif; ?>
});
</script>
<?php $this->endSection(); ?>