<?= $this->extend('Plantillas/plantilla_admin'); ?> 
<!-- Usa la plantilla base. -->

<?= $this->section('titulo'); ?>Reporte de Préstamos Activos<?= $this->endSection(); ?> 
<!-- Título de la página. -->

<?= $this->section('contenido'); ?> 
<!-- Contenido principal. -->

<form method="get" action="">
    <label>Filas por página:</label>
    <input type="number" name="per_page" value="<?= $perPage ?>" min="1" class="form-control w-25">
    <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
</form>
<!-- Formulario para cambiar la cantidad de filas mostradas (paginación). -->

<table class="table table-bordered mt-3">
    <tr><th>Alumno</th><th>Libro</th><th>No.Copia</th><th>Préstamo</th><th>Devolución</th><th>Estado</th></tr>
    <?php foreach($prestamos as $p): ?>
    <tr>
        <td><?= $p['alumno'] ?></td>
        <td><?= $p['titulo'] ?></td>
        <td><?= $p['no_copia'] ?></td>
        <td><?= $p['fecha_prestamo'] ?></td>
        <td><?= $p['fecha_de_devolucion'] ?></td>
        <td><?= $p['estado'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<!-- Tabla con el listado de préstamos activos. -->

<?= $pager->links('default', 'bootstrap_full') ?>
<!-- Paginador con estilo Bootstrap. -->

<form method="post" action="<?= base_url('reportes/activos/pdf') ?>">
    <button type="submit" class="btn btn-danger">Descargar PDF</button>
</form>
<!-- Botón para exportar el reporte a PDF. -->

<?= $this->endSection(); ?> 
<!-- Cierra la sección de contenido. -->
