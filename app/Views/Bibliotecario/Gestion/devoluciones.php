<?= $this->extend('Plantillas/plantilla_biblio'); ?>

<?= $this->section('titulo'); ?>
Gestión de Devoluciones
<?= $this->endSection(); ?>

<?= $this->section('contenido'); ?>

<!-- Mensaje flash -->
<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<!-- Barra de búsqueda -->
<form method="get" action="<?= base_url('devoluciones'); ?>" class="d-flex mb-3">
    <input 
        type="text" 
        name="buscar" 
        class="form-control me-2" 
        placeholder="Buscar por carné, título o número de copia..." 
        value="<?= esc($buscar ?? '') ?>"
    >
    <button type="submit" class="btn btn-success">Buscar</button>
</form>

<!-- Tabla de préstamos -->
<table class="table table-hover table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Carné</th>
            <th>Título</th>
            <th>No. copia</th>
            <th>Fecha préstamo</th>
            <th>Fecha devolución (programada)</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($prestamos)): ?>
            <?php foreach($prestamos as $p): ?>
                <tr>
                    <td><?= esc($p['carne']) ?></td>
                    <td><?= esc($p['titulo']) ?></td>
                    <td><?= esc($p['no_copia']) ?></td>
                    <td><?= esc($p['fecha_prestamo']) ?></td>
                    <td><?= esc($p['fecha_de_devolucion']) ?></td>
                    <td>
                        <!-- Formulario de devolución -->
                        <form method="post" action="<?= base_url('devoluciones/store'); ?>">
                            <input type="hidden" name="prestamo_id" value="<?= esc($p['prestamo_id']) ?>">
                            <input type="date" name="fecha_devuelto" required>
                            <button type="submit" class="btn btn-primary btn-sm">Registrar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center text-muted">No hay préstamos en proceso.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection(); ?>
