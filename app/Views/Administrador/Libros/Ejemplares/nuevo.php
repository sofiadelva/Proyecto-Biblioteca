<?= $this->extend('Plantillas/plantilla_admin') ?> 
<!-- Extiende la plantilla principal. -->

<?= $this->section('titulo') ?>
Agregar Ejemplar
<?= $this->endSection() ?> 
<!-- Sección para el título de la vista. -->

<?= $this->section('contenido') ?> 
<h3>Agregar nuevo ejemplar al libro: <?= esc($libro['titulo']) ?></h3>
<!-- Muestra el título del libro al que se agregará el ejemplar. -->

<form action="<?= base_url('ejemplares/create') ?>" method="post">
    <!-- Formulario que envía al método create para guardar el ejemplar. -->

    <input type="hidden" name="libro_id" value="<?= $libro['libro_id'] ?>">
    <!-- Campo oculto con el ID del libro. -->

    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
            <!-- Selector obligatorio para el estado. -->
            <option value="">Seleccionar estado</option>
            <option value="Disponible">Disponible</option>
            <option value="No Disponible">No Disponible</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="<?= base_url('ejemplares/listar/'.$libro['libro_id']); ?>" class="btn btn-secondary">Cancelar</a>
    <!-- Botones: guardar o cancelar. -->
</form>

<?= $this->endSection() ?> 
<!-- Cierra la sección de contenido. -->
