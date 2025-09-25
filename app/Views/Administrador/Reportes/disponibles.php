<?= $this->extend('Plantillas/plantilla_admin'); ?>  
<!-- Usa la plantilla principal de admin. -->

<?= $this->section('titulo'); ?>Reporte de Libros Disponibles<?= $this->endSection(); ?>  
<!-- Define el título de la vista. -->

<?= $this->section('contenido'); ?>  
<!-- Inicia el contenido principal. -->

<form method="get" action="">
    <label>Filas por página:</label>
    <input type="number" name="per_page" value="<?= $perPage ?>" min="1" class="form-control w-25">
    <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
</form>
<!-- Formulario GET para ajustar cuántos libros mostrar por página. -->

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
<!-- Tabla que lista los libros con sus datos y cantidad disponible. -->

<?= $pager->links('default', 'bootstrap_full') ?>  
<!-- Paginación con estilo Bootstrap. -->

<form method="post" action="<?= base_url('reportes/disponibles/pdf') ?>">
    <button type="submit" class="btn btn-danger">Descargar PDF</button>
</form>
<!-- Botón para generar y descargar el reporte en PDF. -->

<?= $this->endSection(); ?>  
<!-- Cierra la sección de contenido. -->
