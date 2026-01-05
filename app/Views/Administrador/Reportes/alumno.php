<?= $this->extend('Plantillas/plantilla_admin'); ?> 

<?= $this->section('titulo'); ?>Reporte por Alumno<?= $this->endSection(); ?> 

<?= $this->section('contenido'); ?> 

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    <h3 class="section-title mb-3 pb-2 border-bottom">
        <i class="bi bi-person-badge me-2" style="color: #0C1E44;"></i>
        Filtros de Reporte por Alumno
    </h3>
    <form method="get" action="" class="row g-3 align-items-end">
        
        <div class="col-md-6">
    <label for="inputAlumno" class="form-label fw-bold">Alumno (Búsqueda por nombre o carné):</label>
    <input list="lista_alumnos" name="usuario_nombre" id="inputAlumno" 
           value="<?= esc($nombreAlumno ?? ''); ?>" class="form-control" placeholder="Escribe para buscar...">
    <datalist id="lista_alumnos">
        <?php foreach($alumnos as $a): ?>
            <option value="<?= esc($a['nombre']) ?>"><?= esc($a['carne']) ?></option>
        <?php endforeach; ?>
    </datalist>
</div>

        <div class="col-md-3">
            <label for="inputPerPage" class="form-label fw-bold">Filas:</label>
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

<h3 class="mt-4 mb-3">
    Resultados para: 
    <span class="text-secondary fw-bold"><?= esc($nombreAlumno ?: 'Todos los Alumnos'); ?></span>
</h3>

<div class="card shadow-sm border-0" style="border-radius: 12px; overflow-x: auto;">
    <table class="clean-table table-hover"> 
        <thead>
            <tr>
                <th>#</th> <th>Código Libro</th> <th>Título Libro</th>
                <th>No. Copia</th>
                <th>Préstamo</th>
                <th>Devolución Esperada</th>
                <th>Devuelto el</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($prestamos)): ?> 
                <tr><td colspan="8" class="text-center py-4">Sin registros para esta búsqueda.</td></tr>
            <?php else: ?>
                <?php 
                $i = ($pager->getCurrentPage('default') - 1) * $perPage + 1; 
                foreach($prestamos as $p): 
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><span class="badge bg-light text-dark border"><?= esc($p['codigo']) ?></span></td>
                    <td><?= esc($p['titulo']) ?></td>
                    <td><?= esc($p['no_copia']) ?></td>
                    <td><?= esc($p['fecha_prestamo']) ?></td>
                    <td><?= esc($p['fecha_de_devolucion']) ?></td>
                    <td>
                        <?= esc($p['fecha_devuelto'] ?? '-') ?>
                        <?php if ($p['fecha_devuelto'] && $p['fecha_devuelto'] > $p['fecha_de_devolucion']): ?>
                            <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge 
                            <?php 
                                $st = $p['estado'];
                                if ($st == 'Devuelto') echo 'bg-success';
                                elseif ($st == 'Vencido' || $st == 'Perdido') echo 'bg-danger';
                                else echo 'bg-warning text-dark';
                            ?>">
                            <?= esc($st) ?>
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
    
    <form method="post" action="<?= base_url('reportes/alumno/pdf') ?>" target="_blank">
        <input type="hidden" name="usuario_nombre" value="<?= esc($nombreAlumno ?? ''); ?>">
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Descargar PDF
        </button>
    </form>
</div>

<?= $this->endSection(); ?>