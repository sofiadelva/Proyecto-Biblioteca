<?= $this->extend('Plantillas/plantilla_admin'); ?> 

<?= $this->section('titulo'); ?>Reporte por Libro<?= $this->endSection(); ?> 

<?= $this->section('contenido'); ?> 

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    <h3 class="section-title mb-3 pb-2 border-bottom">
        <i class="bi bi-funnel me-2" style="color: #0C1E44;"></i>
        Filtros de Reporte por Libro
    </h3>
    <form method="get" action="" class="row g-3 align-items-end">
        
        <div class="col-md-6">
            <label for="inputLibro" class="form-label fw-bold">Libro (Código o Título):</label>
            <input list="lista_libros" name="libro_titulo" id="inputLibro" 
                value="<?= esc($tituloLibro ?? ''); ?>" class="form-control" placeholder="Escribe el código o título...">
            <datalist id="lista_libros">
                <?php foreach($libros as $l): ?>
                    <option value="<?= esc($l['codigo']) ?>"><?= esc($l['titulo']) ?></option>
                <?php endforeach; ?>
            </datalist>
        </div>

        <div class="col-md-3">
            <label for="inputPerPage" class="form-label fw-bold">Filas por página:</label>
            <input type="number" name="per_page" id="inputPerPage" 
                   value="<?= esc($perPage ?? 10) ?>" min="1" class="form-control">
        </div>
        
        <div class="col-md-3">
            <a href="<?= base_url('reportes/libro') ?>" class="btn btn-outline-secondary btn-sm me-2">Limpiar</a>
            <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Aplicar Filtros</button>
        </div>
    </form>
</div>

<h3 class="mt-4 mb-3">
    Préstamos Históricos de: 
    <span class="text-secondary fw-bold"><?= esc($tituloLibro ?: 'Todos los Libros'); ?></span>
</h3>

<div class="card shadow-sm border-0" style="border-radius: 12px; overflow-x: auto;">
    <table class="clean-table table-hover"> 
        <thead>
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Título</th> 
                <th>Alumno</th>
                <th>No. Copia</th>
                <th>Préstamo</th>
                <th>Devolución Esperada</th>
                <th>Devuelto el</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($prestamos)): ?> 
                <tr><td colspan="9" class="text-center py-4">No se encontraron préstamos.</td></tr>
            <?php else: ?>
                <?php 
                $i = ($pager->getCurrentPage('default') - 1) * $perPage + 1; 
                foreach($prestamos as $p): 
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><span class="badge bg-light text-dark border"><?= esc($p['codigo']) ?></span></td>
                    <td><?= esc($p['titulo']) ?></td> 
                    <td><?= esc($p['alumno']) ?></td>
                    <td><?= esc($p['no_copia']) ?></td>
                    <td><?= esc($p['fecha_prestamo']) ?></td>
                    <td><?= esc($p['fecha_de_devolucion']) ?></td>
                    <td>
                        <?= esc($p['fecha_devuelto'] ?? '-') ?>
                        <?php if ($p['fecha_devuelto'] && $p['fecha_devuelto'] > $p['fecha_de_devolucion']): ?>
                            <i class="bi bi-exclamation-triangle-fill text-danger" title="Devuelto con retraso"></i>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge 
                            <?php 
                                $estado = $p['estado'];
                                if ($estado == 'Devuelto') echo 'bg-success';
                                elseif ($estado == 'Vencido' || $estado == 'Perdido') echo 'bg-danger';
                                else echo 'bg-warning text-dark';
                            ?>">
                            <?= esc($estado) ?>
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
    
    <form method="post" action="<?= base_url('reportes/libro/pdf') ?>" target="_blank">
        <input type="hidden" name="libro_titulo" value="<?= esc($tituloLibro ?? ''); ?>">
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Descargar PDF
        </button>
    </form>
</div>

<?= $this->endSection(); ?>