<?= $this->extend('Plantillas/plantilla_biblio'); ?>

<?= $this->section('titulo'); ?>
Gestión de Libros
<?= $this->endSection(); ?>

<?= $this->section('contenido'); ?>

<!-- ✅ Mensaje flash -->
<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<div class="text-center my-5">
    <h3 class="mb-4">Opciones de Gestión</h3>

    <!-- Botones de navegación -->
    <div class="d-flex justify-content-center gap-4">
        <!-- Agregar Préstamo -->
        <a href="<?= base_url('prestamos'); ?>" class="btn btn-primary btn-lg px-4 py-3" style="background-color:#206060; border:none;">
            <i class="bi bi-journal-plus me-2"></i> Agregar Préstamo
        </a>

        <!-- Agregar Devolución -->
        <a href="<?= base_url('devoluciones'); ?>" class="btn btn-success btn-lg px-4 py-3" style="background-color:#0f7a7a; border:none;">
            <i class="bi bi-arrow-repeat me-2"></i> Agregar Devolución
        </a>
    </div>
</div>

<?= $this->endSection(); ?>
