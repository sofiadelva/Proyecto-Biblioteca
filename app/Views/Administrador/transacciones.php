<?php echo $this->extend('Plantillas/plantilla_admin'); ?> 

<?php $this->section('titulo'); ?>
Transacciones
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<!-- Fila que contiene el botón Agregar y la Barra de Búsqueda -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Botón para agregar una nueva transacción (con el mismo estilo #206060) -->
    <a href="<?= base_url('transacciones/create'); ?>" class="btn btn-lg text-white shadow" style="background-color:#0C1E44;">
        <i class="bi bi-plus-circle-fill me-2"></i>Agregar Transacción
    </a>
    
    <!-- Barra de Búsqueda con diseño especial -->
    <form method="get" action="<?= base_url('transacciones'); ?>" class="search-bar-container">
        <!-- El valor de $buscar se pasa desde el controlador para mantener el término -->
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar por Libro o Usuario..." 
            value="<?= esc($buscar ?? '') ?>" 
        />
        <!-- Campos ocultos para mantener la ordenación y paginación al buscar -->
        <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
        <input type="hidden" name="per_page" value="<?= esc($_GET['per_page'] ?? '') ?>">
        <input type="hidden" name="estado" value="<?= esc($_GET['estado'] ?? '') ?>">

        <button type="submit" class="search-icon">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>
<!-- FIN de la fila superior -->

<div class="row mb-3">
    <!-- Tarjeta de Opciones de Visualización (Ordenar y Filas por página) -->
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-secondary border-opacity-25">
            <div class="card-body py-3">
                <h6 class="card-title text-muted mb-3"><i class="bi bi-sort-alpha-down me-2"></i>Opciones de Visualización</h6>
                
                <form class="d-flex align-items-center mb-3" method="get" action="<?= base_url('transacciones'); ?>">
                    <!-- Filas por página -->
                    <input type="number" name="per_page" value="<?= $perPage ?? 10 ?>" min="1" class="form-control w-auto me-2" style="max-width: 150px;" placeholder="Filas">
                    
                    <!-- Ordenar -->
                    <select name="ordenar" class="form-select w-auto me-2">
                        <option value="">Ordenar por...</option>
                        <option value="fecha_desc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'fecha_desc') ? 'selected' : '' ?>>Préstamo más reciente</option>
                        <option value="fecha_asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'fecha_asc') ? 'selected' : '' ?>>Préstamo más antiguo</option>
                        <option value="titulo_asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'titulo_asc') ? 'selected' : '' ?>>Título A → Z</option>
                        <option value="usuario_asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'usuario_asc') ? 'selected' : '' ?>>Usuario A → Z</option>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-right-short"></i> Aplicar</button>

                    <!-- Campos ocultos para mantener el estado y la búsqueda al ordenar -->
                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                    <input type="hidden" name="estado" value="<?= esc($_GET['estado'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>
    
    <!-- Tarjeta de Filtrado por Estado -->
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-secondary border-opacity-25">
            <div class="card-body py-3">
                <h6 class="card-title text-muted mb-3"><i class="bi bi-funnel-fill me-2"></i>Opciones de Filtrado</h6>
                <form class="d-flex align-items-center" method="get" action="<?= base_url('transacciones'); ?>">
                    <select name="estado" class="form-select w-auto me-2">
                        <option value="">Filtrar por Estado...</option>
                        <option value="En Proceso" <?= (isset($_GET['estado']) && $_GET['estado'] == 'En Proceso') ? 'selected' : '' ?>>En Proceso</option>
                        <option value="Devuelto" <?= (isset($_GET['estado']) && $_GET['estado'] == 'Devuelto') ? 'selected' : '' ?>>Devuelto</option>
                    </select>
                    <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Aplicar Filtro</button>
                    
                    <!-- Campos ocultos para mantener la ordenación, paginación y la búsqueda al filtrar -->
                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                    <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
                    <input type="hidden" name="per_page" value="<?= esc($_GET['per_page'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabla para mostrar las transacciones registradas con diseño clean-table -->
<table class="table clean-table my-3">
    <thead>
        <tr>
            <th>Título del Libro</th>
            <th>Ejemplar</th>
            <th>Usuario</th>
            <th>F. Préstamo</th>
            <th>F. Devolución Est.</th>
            <th>F. Devuelto</th>
            <th>Estado</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($transacciones)): ?>
            <?php foreach($transacciones as $t): ?>
            <tr>
                <td><?= esc($t['titulo']) ?></td>
                <td><?= esc($t['no_copia']) ?></td>
                <td><?= esc($t['usuario_nombre']) ?></td>
                <td><?= esc($t['fecha_prestamo']) ?></td>
                <td><?= esc($t['fecha_de_devolucion']) ?></td>
                <td><?= esc($t['fecha_devuelto'] ?? 'N/A') ?></td>
                
                <!-- COLORES DINÁMICOS PARA EL ESTADO -->
                <td>
                    <?php 
                        $clase_estado = '';
                        // Usamos bg-warning para En Proceso y bg-success para Devuelto
                        if ($t['estado'] == 'En Proceso') {
                            $clase_estado = 'bg-warning text-dark'; 
                        } elseif ($t['estado'] == 'Devuelto') {
                            $clase_estado = 'bg-success text-white'; 
                        } else {
                            // Otro estado, por si acaso
                            $clase_estado = 'bg-secondary text-white';
                        }
                    ?>
                    <span class="badge <?= $clase_estado ?> p-2">
                        <?= esc($t['estado']) ?>
                    </span>
                </td>
                
                <td>
                    <div class="d-flex gap-2">
                        <!-- Botón para editar la transacción -->
                        <a href="<?= base_url('transacciones/edit/'.$t['prestamo_id']); ?>" class="btn-sm btn-accion-editar">Editar</a>
                        
                        <!-- Botón para eliminar la transacción -->
                        <a href="<?= base_url('transacciones/delete/'.$t['prestamo_id']); ?>" class="btn-sm btn-accion-eliminar"
                            onclick="return confirm('¿Seguro que quieres eliminar esta transacción?')">Eliminar</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center text-muted">No se encontraron transacciones con los filtros aplicados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Paginación -->
<div class="mt-4">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>

<?php $this->endSection(); ?>
