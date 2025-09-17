<?php echo $this->extend('Plantillas/plantilla_admin'); ?>

<?php $this->section('titulo'); ?>
Reportes
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<h2>Generar Reportes</h2>

<!-- Reporte por alumno -->
<form action="<?= base_url('reportes/alumno') ?>" method="post" class="mb-4">
    <label for="usuario_nombre">Buscar Alumno:</label>
    <input list="lista_alumnos" name="usuario_nombre" id="usuario_nombre" class="form-control" required>
    <datalist id="lista_alumnos">
        <?php foreach ($alumnos as $a): ?>
            <option value="<?= esc($a['nombre']) ?>"></option>
        <?php endforeach; ?>
    </datalist>
    <button type="submit" class="btn btn-primary mt-2">Generar Reporte por Alumno</button>
</form>

<!-- Reporte por libro -->
<form action="<?= base_url('reportes/libro') ?>" method="post" class="mb-4">
    <label for="libro_titulo">Buscar Libro:</label>
    <input list="lista_libros" name="libro_titulo" id="libro_titulo" class="form-control" required>
    <datalist id="lista_libros">
        <?php foreach ($libros as $l): ?>
            <option value="<?= esc($l['titulo']) ?>"></option>
        <?php endforeach; ?>
    </datalist>
    <button type="submit" class="btn btn-primary mt-2">Generar Reporte por Libro</button>
</form>

<!-- Reporte préstamos activos -->
<form action="<?= base_url('reportes/prestamos-activos') ?>" method="post" class="mb-4">
    <button type="submit" class="btn btn-warning">Generar Reporte de Préstamos Activos</button>
</form>

<!-- Reporte libros disponibles -->
<form action="<?= base_url('reportes/libros-disponibles') ?>" method="post" class="mb-4">
    <button type="submit" class="btn btn-success">Generar Reporte de Libros Disponibles</button>
</form>

<?php $this->endSection(); ?>
