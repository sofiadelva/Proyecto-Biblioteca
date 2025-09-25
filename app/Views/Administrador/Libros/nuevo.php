<?php echo $this->extend('Plantillas/plantilla_admin'); ?>
<!-- Extiende la plantilla base. -->

<?php $this->section('titulo'); ?>
Agregar Libro
<?php $this->endSection(); ?>
<!-- Sección del título de la página. -->

<?php $this->section('contenido'); ?>
<!-- Contenido principal. -->

<form action="<?= base_url('libros/create'); ?>" method="post" class="row g-3" autocomplete="off">
    <!-- Formulario que envía datos al método create. -->

    <div class="col-md-6">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control" name="titulo" required>
    </div>
    <!-- Campo para el título del libro. -->

    <div class="col-md-6">
        <label for="autor" class="form-label">Autor</label>
        <input type="text" class="form-control" name="autor" required>
    </div>
    <!-- Campo para el autor. -->

    <div class="col-md-6">
        <label for="editorial" class="form-label">Editorial</label>
        <input type="text" class="form-control" name="editorial">
    </div>
    <!-- Campo para la editorial (opcional). -->

    <div class="col-md-3">
        <label for="cantidad_total" class="form-label">Cantidad Total</label>
        <input type="number" class="form-control" name="cantidad_total" required>
    </div>
    <!-- Total de ejemplares. -->

    <div class="col-md-3">
        <label for="cantidad_disponibles" class="form-label">Disponibles</label>
        <input type="number" class="form-control" name="cantidad_disponibles" required>
    </div>
    <!-- Ejemplares disponibles. -->

    <div class="col-md-6">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-select" name="estado" required>
            <option value="">Seleccionar</option>
            <option value="Disponible">Disponible</option>
            <option value="Dañado">Dañado</option>
        </select>
    </div>
    <!-- Estado inicial del libro. -->

    <div class="col-md-6">
        <label for="categoria_id" class="form-label">Categoría</label>
        <select class="form-select" name="categoria_id" required>
            <option value="">Seleccionar</option>
            <!-- Itera todas las categorías disponibles -->
            <?php foreach($categorias as $cat): ?>
                <option value="<?= $cat['categoria_id']; ?>"><?= $cat['nombre']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- Selector dinámico de categorías. -->

    <div class="col-12">
        <a href="<?= base_url('libros'); ?>" class="btn btn-secondary">Regresar</a>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
    <!-- Botones: regresar o guardar. -->
</form>

<?php $this->endSection(); ?>
<!-- Cierra la sección de contenido. -->
