<?= $this->extend('Plantillas/plantilla_alumno'); ?>

<?= $this->section('titulo'); ?>
Mis Préstamos
<?= $this->endSection(); ?>

<?= $this->section('contenido'); ?>

<style>
    /* Estilo de la barra de búsqueda (copiado de tu diseño) */
    .search-bar-container {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 20px;
        padding: 5px 15px;
        background-color: #f8f9fa;
        max-width: 400px;
        transition: all 0.3s;
    }

    .search-bar-container:focus-within {
        border-color: var(--color-primary); 
        box-shadow: 0 0 5px rgba(32, 96, 96, 0.5); 
    }

    .search-bar-container input {
        border: none;
        outline: none;
        background: transparent;
        flex-grow: 1;
        padding: 5px 0;
        font-size: 1rem;
    }

    .search-icon {
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding-left: 10px;
    }

    /* Estilos personalizados para la tabla */
    .clean-table th {
        background-color: #206060; /* Color principal de tu tema */
        color: white;
    }
    .clean-table tbody tr:hover {
        background-color: #f1f1f1;
    }
    .bg-success {
        background-color:#61A392 !important;
    }

    .bg-warning {
        background-color:#FFD45C!important;
    }

    .bg-danger {
        background-color:#A01E53!important;
    }
    
</style>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<div class="row mb-3 align-items-center">
    
    <div class="col-md-6 d-flex justify-content-start">
        <form method="get" action="<?= base_url('alumno/prestamos'); ?>" class="search-bar-container">
            <input 
                type="text" 
                name="buscar" 
                placeholder="Buscar por Título..." 
                value="<?= esc($buscar ?? '') ?>" 
            />
            <input type="hidden" name="estado_filtro" value="<?= esc($estadoFiltro ?? '') ?>">
            <input type="hidden" name="per_page" value="<?= esc($perPage ?? '10') ?>">
            
            <button type="submit" class="search-icon">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <div class="col-md-6 d-flex justify-content-end">
        <div class="card shadow-sm border-secondary border-opacity-25 w-100" style="max-width: 350px;">
            <div class="card-body py-3">
                <h6 class="card-title text-muted mb-3"><i class="bi bi-funnel-fill me-2"></i>Filtrar por Estado</h6>
                <form class="d-flex align-items-center" method="get" action="<?= base_url('alumno/prestamos'); ?>">
                    
                    <select name="estado_filtro" class="form-select w-auto me-2">
                        <option value="">Todos los Préstamos</option>
                        <option value="En proceso" <?= (isset($estadoFiltro) && $estadoFiltro == 'En proceso') ? 'selected' : '' ?>>En Proceso</option>
                        <option value="Devuelto" <?= (isset($estadoFiltro) && $estadoFiltro == 'Devuelto') ? 'selected' : '' ?>>Devuelto</option>
                    </select>

                    <button type="submit" class="btn btn-secondary" style="background-color: var(--color-primary); color:white; border:none;"><i class="bi bi-search"></i> Aplicar</button>
                    
                    <input type="hidden" name="buscar" value="<?= esc($buscar ?? '') ?>">
                    <input type="hidden" name="per_page" value="<?= esc($perPage ?? '10') ?>"> 
                </form>
            </div>
        </div>
    </div>
</div>


<table class="table clean-table my-3">
    <thead class="table-dark">
        <tr>
            <th>Título</th>
            <th>No. Copia</th>
            <th>Fecha Préstamo</th>
            <th>Fecha Devolución Estimada</th>
            <th>Fecha Devuelto</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($prestamos)): ?>
            <?php foreach ($prestamos as $prestamo): ?>
                <tr>
                    <td><?= esc($prestamo['titulo']) ?></td>
                    <td><?= esc($prestamo['no_copia']) ?></td>
                    <td><?= date('d/m/Y', strtotime(esc($prestamo['fecha_prestamo']))) ?></td>
                    <td class="<?= (strtotime($prestamo['fecha_de_devolucion']) < strtotime(date('Y-m-d')) && $prestamo['estado'] === 'En proceso') ? 'text-danger fw-bold' : '' ?>">
                        <?= date('d/m/Y', strtotime(esc($prestamo['fecha_de_devolucion']))) ?>
                    </td>
                    <td>
                        <?= $prestamo['fecha_devuelto'] 
                            ? date('d/m/Y', strtotime(esc($prestamo['fecha_devuelto'])))
                            : '<span class="text-muted">Pendiente</span>' ?>
                    </td>
                    <td>
                        <?php 
                            $estado = $prestamo['estado'];
                            
                            // Determinar clase del badge
                            $clase_badge = 'bg-secondary text-white';
                            if ($estado === 'En proceso') $clase_badge = 'bg-warning text-dark';
                            elseif ($estado === 'Devuelto') $clase_badge = 'bg-success text-white';

                            // Lógica de Retraso para libros DEVOLTOS
                            $alerta_retraso = false;
                            if ($estado === 'Devuelto' && !empty($prestamo['fecha_devuelto'])) {
                                $fecha_entrega_real = strtotime(date('Y-m-d', strtotime($prestamo['fecha_devuelto'])));
                                $fecha_limite = strtotime(date('Y-m-d', strtotime($prestamo['fecha_de_devolucion'])));
                                
                                if ($fecha_entrega_real > $fecha_limite) {
                                    $alerta_retraso = true;
                                }
                            }
                        ?>

                        <div class="d-flex flex-column align-items-center">
                            <span class="badge <?= $clase_badge ?> p-2" style="min-width: 90px;">
                                <?= esc($estado) ?>
                            </span>

                            <?php if ($alerta_retraso): ?>
                                <small class="text-danger fw-bold mt-1" style="font-size: 0.75rem; white-space: nowrap;">
                                    <i class="bi bi-clock-history"></i> Con retraso
                                </small>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center text-muted">No tienes préstamos registrados que coincidan con los criterios de búsqueda.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="mt-4">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>

<div class="footer-credits">
                Página realizada por: Sofía del Valle Ajosal y Emily Abril Santizo Urízar - Promo 2025
            </div>

<?= $this->endSection(); ?>