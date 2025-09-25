<!-- Esta vista extiende la plantilla principal del bibliotecario -->
<?= $this->extend('Plantillas/plantilla_biblio'); ?>

<!-- Sección del título de la página -->
<?= $this->section('titulo'); ?>
Gestión de Libros
<?= $this->endSection(); ?>

<!-- Inicio de la sección de contenido -->
<?= $this->section('contenido'); ?>

<!-- Si existe un mensaje flash en la sesión (ejemplo: confirmación o error), se muestra en una alerta -->
<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<!-- Contenedor centrado para las opciones de gestión -->
<div class="text-center my-5">
    <h3 class="mb-4">Opciones de Gestión</h3>

    <!-- Botones de navegación para préstamos y devoluciones -->
    <div class="d-flex justify-content-center gap-4">

        <!-- Botón que redirige a la sección de préstamos -->
        <a href="<?= base_url('prestamos'); ?>" 
           class="btn btn-primary btn-lg px-4 py-3" 
           style="background-color:#206060; border:none;">
            <i class="bi bi-journal-plus me-2"></i> Agregar Préstamo
        </a>

        <!-- Botón que redirige a la sección de devoluciones -->
        <a href="<?= base_url('devoluciones'); ?>" 
           class="btn btn-success btn-lg px-4 py-3" 
           style="background-color:#0f7a7a; border:none;">
            <i class="bi bi-arrow-repeat me-2"></i> Agregar Devolución
        </a>
    </div>
</div>

<!-- Fin de la sección de contenido -->
<?= $this->endSection(); ?>
