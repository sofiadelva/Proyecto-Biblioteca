<?php echo $this->extend('plantilla'); ?>
<?php $this->section('contenido'); ?>

<h3 class="my-3" id="titulo">Libros</h3>

<!-- ✅ Mensaje flash de éxito o error -->
<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<div class="mb-3">
    <a href="<?= base_url('libros/new'); ?>" class="btn btn-success">Agregar</a>

    <!-- 🔹 Ordenar -->
    <form class="d-inline" method="get" action="<?= base_url('libros'); ?>">
        <select name="ordenar" class="form-select d-inline w-auto">
            <option value="">Ordenar por...</option>
            <option value="titulo_asc">Título A → Z</option>
            <option value="titulo_desc">Título Z → A</option>
            <option value="autor_asc">Autor A → Z</option>
            <option value="autor_desc">Autor Z → A</option>
            <option value="reciente">Más reciente</option>
            <option value="viejo">Más viejo</option>
        </select>
        <button type="submit" class="btn btn-primary">Ordenar</button>
    </form>

    <!-- 🔹 Filtrar -->
    <form class="d-inline" method="get" action="<?= base_url('libros'); ?>">
        <select name="estado" class="form-select d-inline w-auto">
            <option value="">Filtrar por estado...</option>
            <option value="Disponible">Disponible</option>
            <option value="Dañado">Dañado</option>
        </select>
        <select name="cantidad_disponible" class="form-select d-inline w-auto">
            <option value="">Filtrar por cantidad...</option>
            <option value="0">0 disponibles</option>
            <option value="1">1 o más disponibles</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filtrar</button>
    </form>
</div>

<table class="table table-hover table-bordered my-3" aria-describedby="titulo">
    <thead class="table-dark">
    <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Editorial</th>
        <th>Cantidad Total</th>
        <th>Cantidad Disponibles</th>
        <th>Estado</th>
        <th>Categoría</th>
        <th>Opciones</th>
    </tr>
    </thead>
<tbody>
    <?php foreach($libros as $libro): ?>
        <tr>
            <td><?= $libro['titulo'] ?></td>
            <td><?= $libro['autor'] ?></td>
            <td><?= $libro['editorial'] ?></td>
            <td><?= $libro['cantidad_total'] ?></td>
            <td><?= $libro['cantidad_disponibles'] ?></td>
            <td><?= $libro['estado'] ?></td>
            <td><?= $libro['categoria'] ?></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="<?= base_url('libros/edit/'.$libro['libro_id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="<?= base_url('libros/delete/'.$libro['libro_id']); ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('¿Seguro que quieres eliminar este libro?')">Eliminar</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
</table>

<?php $this->endSection(); ?>
