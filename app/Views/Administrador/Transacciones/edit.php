<?php echo $this->extend('Plantillas/plantilla_admin'); ?>

<?php $this->section('titulo'); ?>
Editar Transacción
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<form action="<?= base_url('transacciones/update/'.$transaccion['prestamo_id']); ?>" method="post">
    <div class="mb-3">
        <label>Libro</label>
        <select name="libro_id" class="form-select">
            <?php foreach($libros as $l): ?>
                <option value="<?= $l['libro_id'] ?>" <?= $l['libro_id'] == $transaccion['libro_id'] ? 'selected' : '' ?>>
                    <?= $l['titulo'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Ejemplar</label>
        <select name="ejemplar_id" class="form-select">
            <?php foreach($ejemplares as $e): ?>
                <option value="<?= $e['ejemplar_id'] ?>" <?= $e['ejemplar_id'] == $transaccion['ejemplar_id'] ? 'selected' : '' ?>>
                    <?= $e['no_copia'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Usuario</label>
        <select name="usuario_id" class="form-select">
            <?php foreach($usuarios as $u): ?>
                <option value="<?= $u['usuario_id'] ?>" <?= $u['usuario_id'] == $transaccion['usuario_id'] ? 'selected' : '' ?>>
                    <?= $u['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Fecha Préstamo</label>
        <input type="date" name="fecha_prestamo" class="form-control" value="<?= $transaccion['fecha_prestamo'] ?>">
    </div>

    <div class="mb-3">
        <label>Fecha Devolución</label>
        <input type="date" name="fecha_de_devolucion" class="form-control" value="<?= $transaccion['fecha_de_devolucion'] ?>">
    </div>

    <div class="mb-3">
        <label>Fecha Devuelto</label>
        <input type="date" name="fecha_devuelto" class="form-control" value="<?= $transaccion['fecha_devuelto'] ?>">
    </div>

    <div class="mb-3">
        <label>Estado</label>
        <input type="text" name="estado" class="form-control" value="<?= $transaccion['estado'] ?>">
    </div>

    <button type="submit" class="btn btn-success">Actualizar</button>
</form>

<?php $this->endSection(); ?>
