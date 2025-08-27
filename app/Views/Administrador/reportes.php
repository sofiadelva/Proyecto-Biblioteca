<?php echo $this->extend('Plantillas/plantilla_admin'); ?>

<?php $this->section('titulo'); ?>
Reportes
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>

<h2>Generar Reporte de Libros</h2>
<form action="<?= base_url('reportes/generar') ?>" method="post">
    <!-- Aquí puedes agregar filtros si deseas -->
    <button type="submit" class="btn btn-primary mb-3">Generar PDF</button>
</form>

<?php $this->endSection(); ?>
