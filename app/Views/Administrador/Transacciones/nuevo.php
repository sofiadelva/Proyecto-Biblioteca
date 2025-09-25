<?php echo $this->extend('Plantillas/plantilla_admin'); ?>  
<!-- Extiende la plantilla base. -->

<?php $this->section('titulo'); ?>
Nueva Transacción
<?php $this->endSection(); ?>  
<!-- Título de la página. -->

<?php $this->section('contenido'); ?>  
<!-- Contenido principal. -->

<form action="<?= base_url('transacciones/store'); ?>" method="post">
    <!-- Formulario que envía los datos al método store para crear una nueva transacción. -->

    <div class="mb-3">
        <label>Libro</label>
        <select name="libro_id" class="form-select">
            <?php foreach($libros as $l): ?>
                <option value="<?= $l['libro_id'] ?>"><?= $l['titulo'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- Selector para elegir el libro del préstamo. -->

    <div class="mb-3">
        <label>Ejemplar</label>
        <select name="ejemplar_id" class="form-select">
            <?php foreach($ejemplares as $e): ?>
                <option value="<?= $e['ejemplar_id'] ?>"><?= $e['no_copia'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- Selector para elegir el ejemplar específico. -->

    <div class="mb-3">
        <label>Usuario</label>
        <select name="usuario_id" class="form-select">
            <?php foreach($usuarios as $u): ?>
                <option value="<?= $u['usuario_id'] ?>"><?= $u['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- Selector para asignar el préstamo a un usuario. -->

    <div class="mb-3">
        <label>Fecha Préstamo</label>
        <input type="date" name="fecha_prestamo" class="form-control">
    </div>
    <!-- Campo para indicar la fecha del préstamo. -->

    <div class="mb-3">
        <label>Fecha Devolución</label>
        <input type="date" name="fecha_de_devolucion" class="form-control">
    </div>
    <!-- Campo para indicar la fecha programada de devolución. -->

    <div class="mb-3">
        <label>Fecha Devuelto</label>
        <input type="date" name="fecha_devuelto" class="form-control">
    </div>
    <!-- Campo opcional para registrar la fecha real de devolución. -->

    <div class="mb-3">
        <label>Estado</label>
        <input type="text" name="estado" class="form-control">
    </div>
    <!-- Campo para indicar el estado del préstamo (ej. activo, devuelto). -->

    <button type="submit" class="btn btn-success">Guardar</button>
    <!-- Botón para enviar el formulario y crear la transacción. -->
</form>

<?php $this->endSection(); ?>  
<!-- Fin del contenido principal. -->
