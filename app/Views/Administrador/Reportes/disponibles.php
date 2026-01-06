<?= $this->extend('Plantillas/plantilla_admin'); ?> 

<?= $this->section('titulo'); ?>Reporte de Libros Disponibles<?= $this->endSection(); ?> 

<?= $this->section('contenido'); ?> 

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    <h3 class="section-title mb-3 pb-2 border-bottom">
        <i class="bi bi-funnel me-2" style="color: #0C1E44;"></i>
        Filtros de Reporte
    </h3>
    <form method="get" action="" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="inputPerPage" class="form-label fw-bold">Filas por página:</label>
            <input type="number" name="per_page" id="inputPerPage" 
                   value="<?= esc($perPage ?? 10) ?>" min="1" class="form-control">
        </div>
        <div class="col-md-3">
            <a href="<?= base_url('reportes/disponibles') ?>" class="btn btn-outline-secondary btn-sm me-2">Limpiar</a>
            <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Aplicar Filtros</button>
        </div>
    </form>
</div>

<h3 class="mt-4 mb-3">Inventario de Libros Disponibles</h3>

<div class="card shadow-sm border-0" style="border-radius: 12px; overflow-x: auto;">
    <table class="clean-table table-hover"> 
        <thead>
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Editorial</th>
                <th class="text-center">Disponibles</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($libros)): ?> 
                <tr>
                    <td colspan="6" class="text-center py-4">No hay libros con existencias disponibles en este momento.</td>
                </tr>
            <?php else: ?>
                <?php 
                // 2. Columna de conteo de filas dinámica por página
                $i = ($pager->getCurrentPage('default') - 1) * $perPage + 1; 
                foreach($libros as $l): 
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><span class="badge bg-light text-dark border"><?= esc($l['codigo']) ?></span></td>
                    <td class="fw-bold"><?= esc($l['titulo']) ?></td>
                    <td><?= esc($l['autor']) ?></td>
                    <td><?= esc($l['editorial']) ?></td>
                    <td class="text-center">
                        <span class="badge bg-success" style="font-size: 0.9rem;">
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
    
    <form method="post" action="<?= base_url('reportes/disponibles/pdf') ?>" target="_blank">
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Descargar PDF
        </button>
    </form>
</div>

<?= $this->endSection(); ?>