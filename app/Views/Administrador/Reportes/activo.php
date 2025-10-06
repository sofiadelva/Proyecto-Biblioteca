<?= $this->extend('Plantillas/plantilla_admin'); ?> 

<?= $this->section('titulo'); ?>Reporte de Préstamos Activos<?= $this->endSection(); ?> 

<?= $this->section('contenido'); ?> 

<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    <h3 class="section-title mb-3 pb-2 border-bottom">
        <i class="bi bi-funnel me-2" style="color: #206060;"></i>
        Filtros de Reporte
    </h3>
    <form method="get" action="" class="row g-3 align-items-end">
        
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

<h3 class="mt-4 mb-3">Préstamos Activos</h3>
<div class="card shadow-sm border-0" style="border-radius: 12px; overflow-x: auto;">
    <table class="clean-table table-hover"> 
        <thead>
            <tr>
                <th>Alumno</th>
                <th>Libro</th>
                <th>No. Copia</th>
                <th>Préstamo</th>
                <th>Devolución Esperada</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($prestamos)): ?> 
                <tr>
                    <td colspan="6" class="text-center py-4">No hay préstamos activos registrados en este momento.</td>
                </tr>
            <?php else: ?>
                <?php foreach($prestamos as $p): ?>
                <tr>
                    <td><?= esc($p['alumno']) ?></td>
                    <td><?= esc($p['titulo']) ?></td>
                    <td><?= esc($p['no_copia']) ?></td>
                    <td><?= esc($p['fecha_prestamo']) ?></td>
                    <td><?= esc($p['fecha_de_devolucion']) ?></td>
                    <td>
                        <span class="badge 
                            <?php 
                                $estado = $p['estado'];

                                // La lógica del estado para activos:
                                if ($estado == 'Vencido') {
                                    echo 'bg-danger';       // 🔴 Rojo para vencido
                                } else {
                                    echo 'bg-warning text-dark'; // 🟡 Amarillo/Naranja para activo (en proceso)
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
    
    <form method="post" action="<?= base_url('reportes/activos/pdf') ?>">
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