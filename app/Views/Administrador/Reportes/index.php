<?= $this->extend('Plantillas/plantilla_admin'); ?>  
<!-- Hereda la plantilla base. -->

<?= $this->section('titulo'); ?>
Reportes
<?= $this->endSection(); ?>  
<!-- Título de la página. -->

<?= $this->section('contenido'); ?>  
<!-- Contenido principal. -->

<!-- Mensaje flash si existe -->
<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>  

<div class="text-center my-5">
    <h3 class="mb-4">Opciones de Reporte</h3>

    <!-- Botones de navegación a distintos reportes -->
    <div class="d-flex justify-content-center gap-4">
        <a href="<?= base_url('reportes/alumno') ?>" class="btn btn-primary btn-lg px-4 py-3" style="background-color:#206060; border:none;">
            <i class="bi bi-journal-plus me-2"></i> Reporte por Alumno
        </a>

        <a href="<?= base_url('reportes/libro') ?>" class="btn btn-success btn-lg px-4 py-3" style="background-color:#0f7a7a; border:none;">
            <i class="bi bi-arrow-repeat me-2"></i> Reporte por Libro
        </a>

        <a href="<?= base_url('reportes/activos') ?>" class="btn btn-primary btn-lg px-4 py-3" style="background-color:#206060; border:none;">
            <i class="bi bi-journal-plus me-2"></i> Reporte Préstamos Activos
        </a>

        <a href="<?= base_url('reportes/disponibles') ?>" class="btn btn-success btn-lg px-4 py-3" style="background-color:#0f7a7a; border:none;">
            <i class="bi bi-arrow-repeat me-2"></i> Reporte de Libros Disponibles
        </a>
    </div>
</div>
<!-- Cuatro botones principales que redirigen a los distintos reportes disponibles -->

<?= $this->endSection(); ?>  
<!-- Fin del contenido principal. -->
