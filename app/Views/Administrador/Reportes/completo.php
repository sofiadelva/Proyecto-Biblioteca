<?= $this->extend('Plantillas/plantilla_admin'); ?> 

<?= $this->section('titulo'); ?>Reporte Completo de Libros<?= $this->endSection(); ?> 

<?= $this->section('contenido'); ?> 

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    <h3 class="section-title mb-3 pb-2 border-bottom">
        <i class="bi bi-gear me-2" style="color: #0C1E44;"></i>
        Configuración de Vista
    </h3>
    <form method="get" action="" class="row g-3 align-items-end">
        <div class="col-md-2">
            <label class="form-label fw-bold">Filas por página:</label>
            <input type="number" name="per_page" value="<?= esc($perPage) ?>" min="1" class="form-control">
        </div>
        <div class="col-md-3">
            <a href="<?= base_url('reportes/completo') ?>" class="btn btn-outline-secondary btn-sm me-2">Limpiar</a>
            <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Aplicar Filtros</button>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0" style="border-radius: 12px; overflow-x: auto;">
    <table class="clean-table table-hover" style="min-width: 1200px;"> 
        <thead>
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Editorial</th>
                <th>Año</th>
                <th>Págs</th>
                <th>Colección</th>
                <th>Subgénero</th>
                <th>Subcategoría</th>
                <th class="text-center">Total</th>
                <th class="text-center">Disp.</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($libros)): ?> 
                <tr><td colspan="12" class="text-center py-4">No hay libros registrados en la base de datos.</td></tr>
            <?php else: ?>
                <?php $i = ($pager->getCurrentPage('default') - 1) * $perPage + 1; 
                foreach($libros as $l): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><small class="badge bg-light text-dark border"><?= esc($l['codigo']) ?></small></td>
                    <td class="fw-bold"><?= esc($l['titulo']) ?></td>
                    <td><?= esc($l['autor']) ?></td>
                    <td><?= esc($l['editorial']) ?></td>
                    <td class="text-center"><?= esc($l['ano']) ?></td>
                    <td class="text-center"><?= esc($l['paginas']) ?></td>
                    <td><?= esc($l['coleccion_nombre']) ?: '<span class="text-muted">-</span>' ?></td>
                    <td><?= esc($l['subgenero_nombre']) ?: '<span class="text-muted">-</span>' ?></td>
                    <td><?= esc($l['subcategoria_nombre']) ?: '<span class="text-muted">-</span>' ?></td>
                    <td class="text-center"><?= esc($l['cantidad_total']) ?></td>
                    <td class="text-center">
                        <span class="badge <?= ($l['cantidad_disponibles'] >= 1) ? 'bg-success' : 'bg-secondary' ?>">
                            <?= esc($l['cantidad_disponibles']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <?= $pager->links('default', 'bootstrap_full') ?>
    
    <form method="post" action="<?= base_url('reportes/completo/pdf') ?>" target="_blank">
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Descargar PDF
        </button>
    </form>
</div>

<?= $this->endSection(); ?>