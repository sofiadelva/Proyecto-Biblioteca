<?php 
// Extiende de la plantilla principal llamada "plantilla_admin"
echo $this->extend('Plantillas/plantilla_admin'); 
?>

<?php 
// Define la sección "titulo" de la plantilla
$this->section('titulo'); 
?>
Categorías
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido" que se mostrará en el layout
$this->section('contenido'); 
?>

<?php 
if(session()->getFlashdata('msg')): 
?>
    <div class="alert alert-success"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>

<div class="mb-3">
    <!-- Botón para ir a la ruta de creación de una nueva categoría -->
    <a href="<?= base_url('categorias/create'); ?>" class="btn btn-success">Agregar</a>
</div>

<!-- Tabla de categorías -->
<table class="table table-hover table-bordered my-3">
    <thead class="table-dark">
        <tr>
            <th>Nombre</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // Recorre todas las categorías que vienen desde el controlador
        foreach($categorias as $categoria): 
        ?>
        <tr>
            <!-- Nombre de la categoría -->
            <td><?= $categoria['nombre'] ?></td>
            <td>
                <div class="d-flex gap-1">
                    <!-- Botón para editar la categoría -->
                    <a href="<?= base_url('categorias/edit/'.$categoria['categoria_id']); ?>" 
                       class="btn btn-warning btn-sm">Editar</a>

                    <!-- Botón para eliminar la categoría con confirmación -->
                    <a href="<?= base_url('categorias/delete/'.$categoria['categoria_id']); ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Seguro que quieres eliminar esta categoría?')">Eliminar</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php 
$this->endSection(); 
?>
