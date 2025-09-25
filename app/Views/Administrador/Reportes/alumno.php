<?= $this->extend('Plantillas/plantilla_admin'); ?> 
<!-- Hereda la plantilla principal. -->

<?= $this->section('titulo'); ?>Reporte por Alumno<?= $this->endSection(); ?> 
<!-- Define el título de la vista. -->

<?= $this->section('contenido'); ?> 
<!-- Inicia el contenido principal. -->

<form method="get" action="">
    <label>Alumno:</label>
    <input list="lista_alumnos" name="usuario_nombre" value="<?= $nombreAlumno ?>" class="form-control">
    <datalist id="lista_alumnos">
        <?php foreach($alumnos as $a): ?>
            <option value="<?= esc($a['nombre']) ?>"></option>
        <?php endforeach; ?>
    </datalist>
    <!-- Campo para elegir un alumno con lista desplegable. -->

    <label>Filas por página:</label>
    <input type="number" name="per_page" value="<?= $perPage ?>" min="1" class="form-control w-25">
    <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
</form>
<!-- Formulario GET para filtrar por alumno y
