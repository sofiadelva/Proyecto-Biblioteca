<?= $this->extend('Plantillas/plantilla_biblio'); ?>

<?= $this->section('titulo'); ?>
Agregar Préstamo
<?= $this->endSection(); ?>

<?= $this->section('contenido'); ?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('prestamos/store'); ?>">
    <?= csrf_field() ?>

    <!-- Selección de libro -->
    <div class="mb-3">
        <label for="libro_id" class="form-label">Libro</label>
        <select id="libro_id" name="libro_id" class="form-select" required>
            <option value="">Seleccione un libro...</option>
            <?php foreach ($libros as $libro): ?>
                <option value="<?= $libro['libro_id'] ?>">
                    <?= esc($libro['titulo']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Selección de ejemplar disponible -->
    <div class="mb-3">
        <label for="ejemplar_id" class="form-label">Ejemplar (N° copia)</label>
        <select id="ejemplar_id" name="ejemplar_id" class="form-select" required>
            <option value="">Seleccione un ejemplar...</option>
        </select>
    </div>

    <!-- Carné del usuario -->
    <div class="mb-3">
        <label for="carne" class="form-label">Carné del Usuario</label>
        <input type="text" name="carne" id="carne" class="form-control" required>
    </div>

    <!-- Fechas -->
    <div class="mb-3">
        <label for="fecha_prestamo" class="form-label">Fecha de Préstamo</label>
        <input type="date" name="fecha_prestamo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="fecha_de_devolucion" class="form-label">Fecha de Devolución</label>
        <input type="date" name="fecha_de_devolucion" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success" style="background-color:#206060; border:none;">
        Guardar Préstamo
    </button>
</form>

<!-- Script para cargar ejemplares según el libro -->
<script>
document.getElementById('libro_id').addEventListener('change', function() {
    let libroId = this.value;
    let ejemplarSelect = document.getElementById('ejemplar_id');
    ejemplarSelect.innerHTML = '<option value="">Cargando...</option>';

    fetch("<?= base_url('prestamos/getEjemplares'); ?>/" + libroId)
        .then(res => res.json())
        .then(data => {
            ejemplarSelect.innerHTML = '<option value="">Seleccione un ejemplar...</option>';
            data.forEach(ej => {
                ejemplarSelect.innerHTML += `<option value="${ej.ejemplar_id}">Copia #${ej.no_copia}</option>`;
            });
        });
});
</script>

<?= $this->endSection(); ?>
