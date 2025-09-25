<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  
<!-- Extiende la plantilla base. -->

<?php $this->section('titulo'); ?>
Editar Transacción
<?php $this->endSection(); ?>  
<!-- Título de la vista. -->

<?php $this->section('contenido'); ?>  
<!-- Contenido principal. -->

<form action="<?= base_url('transacciones/update/'.$transaccion['prestamo_id']); ?>" method="post">
    <!-- Formulario que envía los datos al método update con el ID de la transacción. -->

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
    <!-- Selector para cambiar el libro de la transacción. -->

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
    <!-- Selector para cambiar el ejemplar asociado. -->

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
    <!-- Selector para cambiar el usuario de la transacción. -->

    <div class="mb-3">
        <label>Fecha Préstamo</label>
        <input type="date" name="fecha_prestamo" class="form-control" value="<?= $transaccion['fecha_prestamo'] ?>">
    </div>
    <!-- Input para modificar la fecha de préstamo. -->

    <div class="mb-3">
        <label>Fecha Devolución</label>
        <input type="date" name="fecha_de_devolucion" class="form-control" value="<?= $transaccion['fecha_de_devolucion'] ?>">
    </div>
    <!-- Input para modificar la fecha de devolución programada. -->

    <div class="mb-3">
        <label>Fecha Devuelto</label>
        <input type="date" name="fecha_devuelto" class="form-control" value="<?= $transaccion['fecha_devuelto'] ?>">
    </div>
    <!-- Input para registrar la fecha real de devolución. -->

    <div class="mb-3">
        <label>Estado</label>
        <input type="text" name="estado" class="form-control" value="<?= $transaccion['estado'] ?>">
    </div>
    <!-- Input para cambiar el estado de la transacción (ej. activo, devuelto, atrasado). -->

    <button type="submit" class="btn btn-success">Actualizar</button>
    <!-- Botón para enviar el formulario y actualizar la transacción. -->
</form>

<?php $this->endSection(); ?>  
<!-- Fin de la sección de contenido. -->
