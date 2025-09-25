<!-- Esta vista hereda/usa la plantilla principal del alumno -->
<?= $this->extend('Plantillas/plantilla_alumno'); ?>

<!-- Sección para el título de la página -->
<?= $this->section('titulo'); ?>
Mis Préstamos
<?= $this->endSection(); ?>

<!-- Sección para el contenido principal -->
<?= $this->section('contenido'); ?>

<!-- Si existe un mensaje flash en la sesión, se muestra en una alerta -->
<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<!-- Tabla donde se listan los préstamos del alumno -->
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
        <!-- Si la variable $prestamos no está vacía, recorre cada préstamo -->
        <?php if (!empty($prestamos)): ?>
            <?php foreach ($prestamos as $prestamo): ?>
                <tr>
                    <!--Se muestra el título del libro -->
                    <td><?= esc($prestamo['titulo']) ?></td>
                    <!-- Número de copia del libro -->
                    <td><?= esc($prestamo['no_copia']) ?></td>
                    <!-- Nombre del alumno -->
                    <td><?= esc($prestamo['alumno']) ?></td>
                    <!-- Fecha en que se hizo el préstamo -->
                    <td><?= esc($prestamo['fecha_prestamo']) ?></td>
                    <!-- Fecha límite para devolverlo -->
                    <td><?= esc($prestamo['fecha_de_devolucion']) ?></td>
                    <!-- Si ya fue devuelto, muestra la fecha; si no, pone "Pendiente" -->
                    <td>
                        <?= $prestamo['fecha_devuelto'] 
                            ? esc($prestamo['fecha_devuelto']) 
                            : '<span class="text-muted">Pendiente</span>' ?>
                    </td>
                    <!-- Estado del préstamo con colores (badge) -->
                    <td>
                        <?php if ($prestamo['estado'] === 'En proceso'): ?>
                            <span class="badge bg-warning text-dark">En proceso</span>
                        <?php elseif ($prestamo['estado'] === 'Devuelto'): ?>
                            <span class="badge bg-success">Devuelto</span>
                        <?php elseif ($prestamo['estado'] === 'Vencido'): ?>
                            <span class="badge bg-danger">Vencido</span>
                        <?php else: ?>
                            <!-- Si hay otro estado no esperado -->
                            <span class="badge bg-secondary"><?= esc($prestamo['estado']) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Si no hay préstamos, muestra un mensaje en la tabla -->
            <tr>
                <td colspan="7" class="text-center text-muted">No tienes préstamos registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Fin de la sección de contenido -->
<?= $this->endSection(); ?>
