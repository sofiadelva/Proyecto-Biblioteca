<?php echo $this->extend('Plantillas/plantilla_admin'); ?> 

<?php $this->section('titulo'); ?>
Gestión de Usuarios
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="<?= base_url('usuarios/create'); ?>" class="btn btn-lg text-white shadow" style="background-color:#206060;">
        <i class="bi bi-person-plus-fill me-2"></i>Agregar Nuevo Usuario
    </a>
    
    <form method="get" action="<?= base_url('usuarios'); ?>" class="search-bar-container">
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar por Nombre, Correo o Carné..." 
            value="<?= esc($buscar ?? '') ?>" 
        />
        <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
        <input type="hidden" name="per_page" value="<?= esc($_GET['per_page'] ?? '') ?>">
        <input type="hidden" name="rol" value="<?= esc($_GET['rol'] ?? '') ?>">

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
                
                <form class="d-flex align-items-center mb-3" method="get" action="<?= base_url('usuarios'); ?>">
                    <input type="number" name="per_page" value="<?= $perPage ?? 10 ?>" min="1" class="form-control w-auto me-2" style="max-width: 150px;" placeholder="Filas">
                    
                    <select name="ordenar" class="form-select w-auto me-2">
                        <option value="">Ordenar por...</option>
                        <option value="nombre_asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'nombre_asc') ? 'selected' : '' ?>>Nombre A → Z</option>
                        <option value="nombre_desc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'nombre_desc') ? 'selected' : '' ?>>Nombre Z → A</option>
                        <option value="reciente" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'reciente') ? 'selected' : '' ?>>Más reciente</option>
                        
                        <option value="correo_asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'correo_asc') ? 'selected' : '' ?>>Email A → Z</option> 
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-right-short"></i> Aplicar</button>

                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                    <input type="hidden" name="rol" value="<?= esc($_GET['rol'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-secondary border-opacity-25">
            <div class="card-body py-3">
                <h6 class="card-title text-muted mb-3"><i class="bi bi-funnel-fill me-2"></i>Filtrar por Rol</h6>
                <form class="d-flex align-items-center" method="get" action="<?= base_url('usuarios'); ?>">
                    <select name="rol" class="form-select w-auto me-2">
                        <option value="">Todos los Roles</option>
                        
                        <?php 
                        // Itera sobre los roles definidos en el controlador
                        foreach($rolesDisponibles as $rol): 
                        ?>
                        <option value="<?= esc($rol) ?>" <?= (isset($_GET['rol']) && $_GET['rol'] == $rol) ? 'selected' : '' ?>>
                            <?= esc($rol) ?>
                        </option>
                        <?php endforeach; ?>

                    </select>
                    <button type="submit" class="btn btn-secondary"><i class="bi bi-filter"></i> Filtrar</button>
                    
                    <input type="hidden" name="buscar" value="<?= esc($_GET['buscar'] ?? '') ?>">
                    <input type="hidden" name="ordenar" value="<?= esc($_GET['ordenar'] ?? '') ?>">
                    <input type="hidden" name="per_page" value="<?= esc($_GET['per_page'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>
</div>

<table class="table clean-table my-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Completo</th>
            <th>Email</th>
            <th>Carné</th> 
            <th>Rol</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($usuarios)): ?>
            <?php foreach($usuarios as $u): ?>
            <tr>
                <td><?= esc($u['usuario_id']) ?></td>
                <td><?= esc($u['nombre']) ?></td>
                <td><?= esc($u['correo']) ?></td> 
                <td><?= esc($u['carne']) ?></td> 
                <td>
                    <?php 
                        $clase_rol = 'bg-secondary-subtle text-dark'; // Base/default
                        
                        // COLORES CÁLIDOS Y SUAVES
                        if (strtolower($u['rol']) == 'administrador') {
                            $clase_rol = 'bg-danger-subtle text-danger'; // Rojo/rosa muy suave
                        } elseif (strtolower($u['rol']) == 'bibliotecario') {
                            $clase_rol = 'bg-warning-subtle text-dark'; // Amarillo muy suave
                        } elseif (strtolower($u['rol']) == 'alumno') {
                            $clase_rol = 'bg-info-subtle text-primary'; // Azul cielo muy suave
                        }
                    ?>
                    <span class="badge <?= $clase_rol ?> p-2">
                        <?= esc($u['rol']) ?>
                    </span>
                </td>
                
                <td>
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('usuarios/edit/'.$u['usuario_id']); ?>" class="btn-sm btn-accion-editar">Editar</a>
                        
                        <a href="<?= base_url('usuarios/delete/'.$u['usuario_id']); ?>" class="btn-sm btn-accion-eliminar"
                            onclick="return confirm('¿Seguro que quieres eliminar a este usuario?')">Eliminar</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center text-muted">No se encontraron usuarios con los filtros aplicados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="mt-4">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>

<?php $this->endSection(); ?>