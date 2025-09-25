<?php echo $this->extend('Plantillas/plantilla_admin'); ?> 
<!-- Usa la plantilla base "plantilla_admin". -->

<?php $this->section('titulo'); ?>
Editar Libro
<?php $this->endSection(); ?> 
<!-- Título de la página. -->

<?php $this->section('contenido'); ?> 
<!-- Contenido principal. -->

<form action="<?= base_url('libros/update/'.$libro['libro_id']); ?>" method="post" class="row g-3" autocomplete="off">
    <!-- Formulario que envía los datos al método update con el id del libro. -->

    <div class="col-md-6">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control" name="titulo" value="<?= $libro['titulo'] ?>" required>
    </div>
    <!-- Campo de texto para el título del libro. -->

    <div class="col-md-6">
        <label for="autor" class="form-label">Autor</label>
        <input type="text" class="form-control" name="autor" value="<?= $libro['autor'] ?>" required>
    </div>
    <!-- Campo de texto para el autor. -->

    <div class="col-md-6">
        <label for="editorial" class="form-label">Editorial</label>
        <input type="text" class="form-control" name="editorial" value="<?= $libro['editorial'] ?>">
    </div>
    <!-- Campo para la editorial (opcional). -->

    <div class="col-md-3">
        <label for="cantidad_total" class="form-label">Cantidad Total</label>
        <input type="number" class="form-control" id="cantidad_total" name="cantidad_total" value="<?= $libro['cantidad_total'] ?>" required>
    </div>
    <!-- Número total de ejemplares. -->

    <div class="col-md-3">
        <label for="cantidad_disponibles" class="form-label">Disponibles</label>
        <input type="number" class="form-control" name="cantidad_disponibles" value="<?= $libro['cantidad_disponibles'] ?>" required>
    </div>
    <!-- Número de ejemplares disponibles. -->

    <div class="col-md-6">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-select" name="estado" required>
            <option value="Disponible" <?= $libro['estado']=="Disponible" ? 'selected':''; ?>>Disponible</option>
            <option value="Dañado" <?= $libro['estado']=="Dañado" ? 'selected':''; ?>>Dañado</option>
        </select>
    </div>
    <!-- Selector para estado del libro. -->

    <div class="col-md-6">
        <label for="categoria_id" class="form-label">Categoría</label>
        <select class="form-select" name="categoria_id" required>
            <option value="">Seleccionar</option>
            <?php foreach($categorias as $cat): ?>
                <!-- Marca seleccionada la categoría actual del libro -->
                <option value="<?= $cat['categoria_id']; ?>"
                    <?= $libro['categoria_id'] == $cat['categoria_id'] ? 'selected' : '' ?>>
                    <?= $cat['nombre']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- Selector de categoría (lista generada desde la BD). -->

    <div class="col-12">
        <a href="<?= base_url('libros'); ?>" class="btn btn-secondary">Regresar</a>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </div>
    <!-- Botones: regresar o actualizar. -->

</form>

<?php $this->endSection(); ?> 
<!-- Cierra la sección de contenido. -->
