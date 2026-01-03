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
    
    <!-- Barra de Búsqueda con diseño especial -->
    <form method="get" action="<?= base_url('transacciones'); ?>" class="search-bar-container">
        <!-- El valor de $buscar se pasa desde el controlador para mantener el término -->
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar por Código, Libro, Carne o Usuario..." 
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
                    </select>
                    <div class="d-flex justify-content-end mt-3">
                        <a href="<?= base_url('transacciones') ?>" class="btn btn-outline-secondary btn-sm me-2">Limpiar</a>
                        <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Aplicar Filtros</button>
                    </div>

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
                    
                    <div class="d-flex justify-content-end mt-3">
                        <a href="<?= base_url('transacciones') ?>" class="btn btn-outline-secondary btn-sm me-2">Limpiar</a>
                        <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Aplicar Filtros</button>
                    </div>
                    
                    <!-- Campos ocultos para mantener la ordenación, paginación y la búsqueda al filtrar -->
                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                    <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
                    <input type="hidden" name="per_page" value="<?= esc($_GET['per_page'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
    $request = \Config\Services::request();
    // Intentamos obtener la página actual, si no existe es la 1
    $paginaActual = $request->getVar('page') ?? 1;
    $paginaActual = (int)$paginaActual;
    $porPagina = $perPage ?? 10; 
    $contador = ($paginaActual - 1) * $porPagina + 1;
?>
<!-- Tabla para mostrar las transacciones registradas con diseño clean-table -->
<table class="table clean-table my-3">
    
    <thead>
        <tr>
            <th>#</th>
            <th>Código</th>
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
                <td class="fw-bold text-muted"><?= $contador++ ?></td>
                <td><?= esc($t['codigo']) ?></td>
                <td><?= esc($t['titulo']) ?></td>
                <td><?= esc($t['no_copia']) ?></td>
                <td>
                    <div class="fw-bold"><?= esc($t['usuario_nombre']) ?></div>
                    <small class="text-muted"><?= esc($t['carne']) ?></small>
                </td>
                <td><?= esc($t['fecha_prestamo']) ?></td>
                <td><?= esc($t['fecha_de_devolucion']) ?></td>
                <td><?= esc($t['fecha_devuelto'] ?? 'N/A') ?></td>
                
                <!-- COLORES DINÁMICOS PARA EL ESTADO -->
                <td>
                    <?php 
                        $clase_estado = '';
                        $mostrar_retraso = false;

                        // Limpiamos los valores de estado para comparar
                        $estado_limpio = trim($t['estado']);

                        if ($estado_limpio == 'En Proceso') {
                            $clase_estado = 'bg-warning text-dark'; 
                        } elseif ($estado_limpio == 'Devuelto') {
                            $clase_estado = 'bg-success text-white'; 

                            // LÓGICA DE RETRASO: Solo si ya está devuelto
                            if (!empty($t['fecha_devuelto']) && !empty($t['fecha_de_devolucion'])) {
                                // Convertimos a tiempo para comparar
                                $f_esperada = strtotime($t['fecha_de_devolucion']);
                                $f_real = strtotime($t['fecha_devuelto']);
                                
                                if ($f_real > $f_esperada) {
                                    $mostrar_retraso = true;
                                }
                            }
                        } else {
                            $clase_estado = 'bg-secondary text-white';
                        }
                    ?>
                    
                    <div class="d-flex flex-column align-items-center">
                        <span class="badge <?= $clase_estado ?> p-2">
                            <?= esc($t['estado']) ?>
                        </span>
                        
                        <?php if ($mostrar_retraso): ?>
                            <small class="text-danger fw-bold mt-1" style="font-size: 0.75rem;">
                                <i class="bi bi-clock-history"></i> Con retraso
                            </small>
                        <?php endif; ?>
                    </div>
                </td>
                
                <td>
                    <div class="d-flex gap-2">
                        <!-- Botón para editar la transacción -->
                        <a href="<?= base_url('transacciones/edit/'.$t['prestamo_id']); ?>" class="btn-sm btn-accion-editar">Editar</a>
                        
                        <!-- Botón para eliminar la transacción -->
                        <a href="<?= base_url('transacciones/delete/'.$t['prestamo_id']); ?>" 
                            class="btn-sm btn-accion-eliminar"
                            onclick="return confirm('¿Estás seguro de eliminar este registro? \n\nSi el préstamo está activo (En Proceso), el libro se devolverá automáticamente al inventario.')">
                            <i class="bi"></i> Eliminar
                        </a>
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
