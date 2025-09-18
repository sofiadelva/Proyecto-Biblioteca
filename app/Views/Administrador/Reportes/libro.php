<?= $this->extend('Plantillas/plantilla_admin'); ?>
<?= $this->section('titulo'); ?>Reporte por Libro<?= $this->endSection(); ?>
<?= $this->section('contenido'); ?>

<form method="get" action="">
    <label>Libro:</label>
    <input list="lista_libros" name="libro_titulo" value="<?= $tituloLibro ?>" class="form-control">
    <datalist id="lista_libros">
        <?php foreach($libros as $l): ?>
            <option value="<?= esc($l['titulo']) ?>"></option>
        <?php endforeach; ?>
    </datalist>

    <label class="mt-2">Filas por página:</label>
    <input type="number" name="per_page" value="<?= $perPage ?>" min="1" class="form-control w-25">
    <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
</form>

<table class="table table-bordered mt-3">
    <tr><th>Alumno</th><th>No.Copia</th><th>Préstamo</th><th>Devolución</th><th>Devuelto</th><th>Estado</th></tr>
    <?php foreach($prestamos as $p): ?>
    <tr>
        <td><?= $p['alumno'] ?></td>
        <td><?= $p['no_copia'] ?></td>
        <td><?= $p['fecha_prestamo'] ?></td>
        <td><?= $p['fecha_de_devolucion'] ?></td>
        <td><?= $p['fecha_devuelto'] ?? '-' ?></td>
        <td><?= $p['estado'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $pager->links('default', 'bootstrap_full') ?>

<form method="post" action="<?= base_url('reportes/libro/pdf') ?>">
    <input type="hidden" name="libro_titulo" value="<?= $tituloLibro ?>">
    <button type="submit" class="btn btn-danger">Descargar PDF</button>
</form>

<?= $this->endSection(); ?>
