<?= $this->extend('Plantillas/plantilla_admin') ?> 
<!-- Extiende la vista base "plantilla_admin". -->

<?= $this->section('titulo') ?> 
Editar Ejemplar
<?= $this->endSection() ?> 
<!-- Sección para el título de la página. -->

<?= $this->section('contenido') ?> 
<!-- Sección donde va el contenido principal. -->

<form action="<?= base_url('ejemplares/update/'.$ejemplar['ejemplar_id']) ?>" method="post">
    <!-- Formulario que envía datos al método update con el id del ejemplar. -->

    <input type="hidden" name="libro_id" value="<?= $libro['libro_id'] ?>">
    <!-- Campo oculto para no perder la relación con el libro. -->

    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
            <!-- Selector del estado, se marca "selected" si coincide con el actual. -->

            <option value="Disponible" <?= ($ejemplar['estado'] == 'Disponible') ? 'selected' : '' ?>>Disponible</option>
            <option value="No Disponible" <?= ($ejemplar['estado'] == 'No Disponible') ? 'selected' : '' ?>>No Disponible</option>
        </select>
    </div>

    <button type="submit" class="btn btn-warning">Actualizar</button>
    <a href="<?= base_url('ejemplares/listar/'.$libro['libro_id']); ?>" class="btn btn-secondary">Cancelar</a>
    <!-- Botones para actualizar o volver al listado. -->
</form>

<?= $this->endSection() ?> 
<!-- Cierra la sección de contenido. -->
 