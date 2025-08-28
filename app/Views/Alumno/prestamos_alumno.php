<?= $this->extend('Plantillas/plantilla_alumno'); ?>

<?= $this->section('titulo'); ?>
Mis Préstamos
<?= $this->endSection(); ?>

<?= $this->section('contenido'); ?>

<!-- ✅ Mensaje flash -->
<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<!-- 🔹 Tabla de préstamos -->
<table class="table table-hover table-bordered my-3">
    <thead class="table-dark">
        <tr>
            <th>Título</th>
            <th>No. Copia</th>
            <th>Alumno</th>
            <th>Fecha de Préstamo</th>
            <th>Fecha de Devolución</th>
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
                    <td><?= esc($prestamo['alumno']) ?></td>
                    <td><?= esc($prestamo['fecha_prestamo']) ?></td>
                    <td><?= esc($prestamo['fecha_de_devolucion']) ?></td>
                    <td>
                        <?= $prestamo['fecha_devuelto'] 
                            ? esc($prestamo['fecha_devuelto']) 
                            : '<span class="text-muted">Pendiente</span>' ?>
                    </td>
                    <td>
                        <?php if ($prestamo['estado'] === 'En proceso'): ?>
                            <span class="badge bg-warning text-dark">En proceso</span>
                        <?php elseif ($prestamo['estado'] === 'Devuelto'): ?>
                            <span class="badge bg-success">Devuelto</span>
                        <?php elseif ($prestamo['estado'] === 'Vencido'): ?>
                            <span class="badge bg-danger">Vencido</span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><?= esc($prestamo['estado']) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center text-muted">No tienes préstamos registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection(); ?>
