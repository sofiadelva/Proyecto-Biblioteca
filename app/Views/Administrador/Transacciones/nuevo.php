<?php echo $this->extend('Plantillas/plantilla_admin'); ?>

<?php $this->section('titulo'); ?>
Nueva Transacción
<?php $this->endSection(); ?>

<?php $this->section('contenido'); ?>

<form action="<?= base_url('transacciones/store'); ?>" method="post">
    <div class="mb-3">
        <label>Libro</label>
        <select name="libro_id" class="form-select">
            <?php foreach($libros as $l): ?>
                <option value="<?= $l['libro_id'] ?>"><?= $l['titulo'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Ejemplar</label>
        <select name="ejemplar_id" class="form-select">
            <?php foreach($ejemplares as $e): ?>
                <option value="<?= $e['ejemplar_id'] ?>"><?= $e['no_copia'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Usuario</label>
        <select name="usuario_id" class="form-select">
            <?php foreach($usuarios as $u): ?>
                <option value="<?= $u['usuario_id'] ?>"><?= $u['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Fecha Préstamo</label>
        <input type="date" name="fecha_prestamo" class="form-control">
    </div>

    <div class="mb-3">
        <label>Fecha Devolución</label>
        <input type="date" name="fecha_de_devolucion" class="form-control">
    </div>

    <div class="mb-3">
        <label>Fecha Devuelto</label>
        <input type="date" name="fecha_devuelto" class="form-control">
    </div>

    <div class="mb-3">
        <label>Estado</label>
        <input type="text" name="estado" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
</form>

<?php $this->endSection(); ?>
