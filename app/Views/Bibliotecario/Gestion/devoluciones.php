<!-- Esta vista extiende la plantilla principal del bibliotecario -->
<?= $this->extend('Plantillas/plantilla_biblio'); ?>

<!-- Sección del título de la página -->
<?= $this->section('titulo'); ?>
Gestión de Devoluciones
<?= $this->endSection(); ?>

<!-- Sección del contenido principal -->
<?= $this->section('contenido'); ?>

<!-- Si existe un mensaje flash en la sesión, se muestra en una alerta -->
<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<!-- Formulario de búsqueda (permite buscar devoluciones por carné, título o número de copia) -->
<form method="get" action="<?= base_url('devoluciones'); ?>" class="d-flex mb-3">
    <input 
        type="text" 
        name="buscar" 
        class="form-control me-2" 
        placeholder="Buscar por carné, título o número de copia..." 
        value="<?= esc($buscar ?? '') ?>" <!-- Mantiene el texto buscado si ya se envió -->
    >
    <button type="submit" class="btn btn-success">Buscar</button>
</form>

<!-- Tabla que lista los préstamos que están pendientes de devolución -->
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
        <!-- Si existen préstamos en la variable $prestamos -->
        <?php if (!empty($prestamos)): ?>
            <!-- Recorremos cada préstamo -->
            <?php foreach($prestamos as $p): ?>
                <tr>
                    <!-- Se muestran los datos principales del préstamo -->
                    <td><?= esc($p['carne']) ?></td>
                    <td><?= esc($p['titulo']) ?></td>
                    <td><?= esc($p['no_copia']) ?></td>
                    <td><?= esc($p['fecha_prestamo']) ?></td>
                    <td><?= esc($p['fecha_de_devolucion']) ?></td>
                    <td>
                        <!-- Formulario para registrar la devolución del libro -->
                        <form method="post" action="<?= base_url('devoluciones/store'); ?>">
                            <!-- Se envía el id del préstamo -->
                            <input type="hidden" name="prestamo_id" value="<?= esc($p['prestamo_id']) ?>">
                            <!-- Fecha en la que se devuelve realmente el libro -->
                            <input type="date" name="fecha_devuelto" required>
                            <!-- Botón para registrar la devolución -->
                            <button type="submit" class="btn btn-primary btn-sm">Registrar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Si no hay préstamos en proceso, se muestra un mensaje -->
            <tr>
                <td colspan="6" class="text-center text-muted">No hay préstamos en proceso.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Fin de la sección de contenido -->
<?= $this->endSection(); ?>
