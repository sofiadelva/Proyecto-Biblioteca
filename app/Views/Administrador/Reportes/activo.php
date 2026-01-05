<?= $this->extend('Plantillas/plantilla_admin'); ?> 

<?= $this->section('titulo'); ?>Reporte de Préstamos Activos<?= $this->endSection(); ?> 

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
            <button type="submit" class="btn btn-primary w-100" style="background-color:#0C1E44; border-color:#0C1E44;">
                <i class="bi bi-search"></i> Filtrar
            </button>
        </div>
    </form>
</div>

<h3 class="mt-4 mb-3">Préstamos Activos actualmente</h3>

<div class="card shadow-sm border-0" style="border-radius: 12px; overflow-x: auto;">
    <table class="clean-table table-hover"> 
        <thead>
            <tr>
                <th>#</th>
                <th>Alumno</th>
                <th>Código Libro</th>
                <th>Libro</th>
                <th>No. Copia</th>
                <th>Fecha Préstamo</th>
                <th>Entrega Esperada</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($prestamos)): ?> 
                <tr>
                    <td colspan="8" class="text-center py-4">No hay préstamos activos registrados.</td>
                </tr>
            <?php else: ?>
                <?php 
                $i = ($pager->getCurrentPage('default') - 1) * $perPage + 1; 
                foreach($prestamos as $p): 
                    // Determinamos si hay retraso (si hoy es estrictamente mayor a la fecha de devolución)
                    $esRetraso = ($hoy > $p['fecha_de_devolucion']);
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td>
                        <div class="fw-bold text-dark"><?= esc($p['alumno']) ?></div>
                        <small class="text-muted"><i class="bi me-1"></i><?= esc($p['carne']) ?></small>
                    </td>
                    <td><span class="badge bg-light text-dark border"><?= esc($p['codigo']) ?></span></td>
                    <td><?= esc($p['titulo']) ?></td>
                    <td class="text-center"><?= esc($p['no_copia']) ?></td>
                    <td class="text-center"><?= esc($p['fecha_prestamo']) ?></td>
                    <td class="text-center">
                        <span class="<?= $esRetraso ? 'text-danger fw-bold' : '' ?>">
                            <?= esc($p['fecha_de_devolucion']) ?>
                            <?php if ($esRetraso): ?>
                                <i class="bi bi-exclamation-circle-fill ms-1" title="Fecha vencida"></i>
                            <?php endif; ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge <?= $esRetraso ? 'bg-danger' : 'bg-warning text-dark' ?>">
                            <i class="bi <?= $esRetraso ? 'bi-patch-exclamation' : 'bi-clock-history' ?> me-1"></i>
                            <?= esc($p['estado']) ?> </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <?= $pager->links('default', 'bootstrap_full') ?>
    
    <form method="post" action="<?= base_url('reportes/activos/pdf') ?>" target="_blank">
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Descargar PDF
        </button>
    </form>
</div>

<?= $this->endSection(); ?>