<?= $this->extend('Plantillas/plantilla_admin'); ?>
<?= $this->section('titulo'); ?>Reporte de Libros Disponibles<?= $this->endSection(); ?>
<?= $this->section('contenido'); ?>

<form method="get" action="">
    <label>Filas por página:</label>
    <input type="number" name="per_page" value="<?= $perPage ?>" min="1" class="form-control w-25">
    <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
</form>

<table class="table table-bordered mt-3">
    <tr><th>Título</th><th>Autor</th><th>Editorial</th><th>Cantidad</th></tr>
    <?php foreach($libros as $l): ?>
    <tr>
        <td><?= $l['titulo'] ?></td>
        <td><?= $l['autor'] ?></td>
        <td><?= $l['editorial'] ?></td>
        <td><?= $l['cantidad_disponibles'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $pager->links('default', 'bootstrap_full') ?>

<form method="post" action="<?= base_url('reportes/disponibles/pdf') ?>">
    <button type="submit" class="btn btn-danger">Descargar PDF</button>
</form>

<?= $this->endSection(); ?>
