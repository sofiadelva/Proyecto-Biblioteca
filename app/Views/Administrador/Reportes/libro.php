<?= $this->extend('Plantillas/plantilla_admin'); ?> 

<?= $this->section('titulo'); ?>Reporte por Libro<?= $this->endSection(); ?> 

<?= $this->section('contenido'); ?> 

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    <h3 class="section-title mb-3 pb-2 border-bottom">
        <i class="bi bi-funnel me-2" style="color: #206060;"></i>
        Filtros de Reporte por Libro
    </h3>
    <form method="get" action="" class="row g-3 align-items-end">
        
        <div class="col-md-6">
            <label for="inputLibro" class="form-label fw-bold">Libro:</label>
            <input list="lista_libros" name="libro_titulo" id="inputLibro" 
                   value="<?= esc($tituloLibro ?? ''); ?>" class="form-control" placeholder="Escribe para buscar un título...">
            <datalist id="lista_libros">
                <?php foreach($libros as $l): ?>
                    <option value="<?= esc($l['titulo']) ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </div>

        <div class="col-md-3">
            <label for="inputPerPage" class="form-label fw-bold">Filas por página:</label>
            <input type="number" name="per_page" id="inputPerPage" 
                   value="<?= esc($perPage ?? 10) ?>" min="1" class="form-control">
        </div>
        
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100" style="background-color:#095959; border-color:#095959;">
                <i class="bi bi-search"></i> Filtrar
            </button>
        </div>
    </form>
</div>

<h3 class="mt-4 mb-3">
    Préstamos Históricos de: 
    <span class="text-secondary fw-bold"><?= esc($tituloLibro ?? 'Todos los Libros'); ?></span>
</h3>
<div class="card shadow-sm border-0" style="border-radius: 12px; overflow-x: auto;">
    <table class="clean-table table-hover"> 
        <thead>
            <tr>
                <th>Título</th> <th>Alumno</th>
                <th>No. Copia</th>
                <th>Préstamo</th>
                <th>Devolución Esperada</th>
                <th>Devuelto el</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($prestamos)): ?> 
                <tr>
                    <td colspan="7" class="text-center py-4">No se encontraron préstamos que coincidan con el filtro.</td>
                </tr>
            <?php else: ?>
                <?php foreach($prestamos as $p): ?>
                <tr>
                    <td><?= esc($p['titulo']) ?></td> 
                    <td><?= esc($p['alumno']) ?></td>
                    <td><?= esc($p['no_copia']) ?></td>
                    <td><?= esc($p['fecha_prestamo']) ?></td>
                    <td><?= esc($p['fecha_de_devolucion']) ?></td>
                    <td><?= esc($p['fecha_devuelto'] ?? '-') ?></td>
                    <td>
                        <span class="badge 
                            <?php 
                                $estado = $p['estado'];

                                // Lógica de colores del estado
                                if ($estado == 'Devuelto') {
                                    echo 'bg-success';      // 🟢 Verde
                                } elseif ($estado == 'Vencido' || $estado == 'Perdido') {
                                    echo 'bg-danger';       // 🔴 Rojo
                                } else {
                                    echo 'bg-warning text-dark'; // 🟡 Amarillo/Naranja (Activo/En Proceso/Prestado)
                                }
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

<?php $this->section('head'); ?>
<style>
    .section-title {
        color: #206060;
        font-weight: 700;
        font-size: 1.75rem;
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 15px;
        box-shadow: none !important;
        border: 1px solid #ced4da;
    }
    .btn-primary {
        background-color: #095959; 
        border-color: #095959;
    }
    .clean-table thead th {
        background-color: #095959; 
        color: #ffffff;
        font-weight: 600;
        padding: 15px 20px;
    }
</style>
<?php $this->endSection(); ?>