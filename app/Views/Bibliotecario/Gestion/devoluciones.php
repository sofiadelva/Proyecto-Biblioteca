<?php 
// Extiende de la plantilla principal
echo $this->extend('Plantillas/plantilla_admin'); 
?>

<?php 
// Define la sección "titulo" de la plantilla
$this->section('titulo'); 
?>
Gestión de Devoluciones
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido"
$this->section('contenido'); 
?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error_msg')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error_msg') ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0 text-dark"><i class="bi bi-arrow-left-right me-2"></i>Préstamos Activos para Devolución</h3>
    
    <form method="get" action="<?= base_url('devoluciones'); ?>" class="search-bar-container">
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar por Título, Carné o Usuario..." 
            value="<?= esc($buscar ?? '') ?>" 
        />
        <input type="hidden" name="per_page" value="<?= esc($perPage ?? 10) ?>">
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
                
                <form class="d-flex align-items-center" method="get" action="<?= base_url('devoluciones'); ?>">
                    <input type="number" name="per_page" value="<?= $perPage ?? 10 ?>" min="1" class="form-control w-auto me-2" style="max-width: 150px;" placeholder="Filas">
                    
                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-right-short"></i> Aplicar</button>
                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table clean-table my-3">
        <thead>
        <tr>
            <th>Libro</th>
            <th>Copia</th>
            <th>Usuario (Carné)</th>
            <th>Fecha Préstamo</th>
            <th>Fecha Límite</th>
            <th>Estado de Entrega</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        if (empty($prestamos)): ?>
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="bi bi-info-circle me-2"></i> No hay préstamos pendientes de devolución que coincidan con los criterios.
                </td>
            </tr>
        <?php 
        else:
            foreach($prestamos as $prestamo): 
                $hoy = new DateTime();
                $limite = new DateTime($prestamo['fecha_de_devolucion']);
                $intervalo = $hoy->diff($limite);
                $esAtrasado = $hoy > $limite;
                $claseFila = $esAtrasado ? 'table-danger-light' : ''; // Resalta la fila si está atrasado
        ?>
            <tr class="<?= $claseFila ?>">
                <td><?= esc($prestamo['titulo']) ?></td>
                <td>N° <?= esc($prestamo['no_copia']) ?></td>
                <td>
                    <?= esc($prestamo['nombre_usuario']) ?> 
                    <small class="text-muted d-block">(<?= esc($prestamo['carne']) ?>)</small>
                </td>
                <td><?= esc($prestamo['fecha_prestamo']) ?></td>
                <td>
                    <strong><?= esc($prestamo['fecha_de_devolucion']) ?></strong>
                </td>
                <td>
                    <?php if ($esAtrasado): ?>
                        <span class="badge bg-danger">
                            ATRASADO por <?= esc($intervalo->days) ?> días
                        </span>
                    <?php else: ?>
                        <span class="badge bg-success">
                            VIGENTE (<?= esc($intervalo->days) ?> días restantes)
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?= base_url('devoluciones/confirmar/'.$prestamo['prestamo_id']); ?>" 
                        class="btn btn-sm text-white shadow-sm"
                        style="background-color:#206060;"
                        onclick="return confirm('¿Confirmar la devolución del libro [<?= esc($prestamo['titulo']) ?>] por parte de [<?= esc($prestamo['nombre_usuario']) ?>]?')">
                        <i class="bi bi-box-arrow-in-down-right me-1"></i> Devolver
                    </a>
                </td>
            </tr>
        <?php endforeach; 
        endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-4">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>

<?php 
$this->endSection(); 
?>