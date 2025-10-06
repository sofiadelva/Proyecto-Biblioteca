<?= $this->extend('Plantillas/plantilla_admin'); ?> 
<?= $this->section('titulo'); ?>
Ejemplares de <?= esc($libro['titulo']) ?>
<?= $this->endSection(); ?> 
<?= $this->section('contenido'); ?> 
<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    
    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-list-columns-reverse me-2" style="color: #206060;"></i>
        Ejemplares de: <span style="font-weight: 500;"><?= esc($libro['titulo']) ?></span>
    </h2>
    
    <?php if(session()->getFlashdata('msg')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        
        <a href="<?= base_url('ejemplares/new/'.$libro['libro_id']) ?>" class="btn btn-lg text-white shadow" style="background-color:#206060;">
            <i class="bi bi-plus-circle-fill me-2"></i>Agregar Nuevo Ejemplar
        </a>
        
        <a href="<?= base_url('libros') ?>" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left-short"></i> Volver a Libros
        </a>
    </div>

    <table class="table clean-table my-3">
        <thead>
            <tr>
                <th># Copia</th>
                <th>Título del Libro</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($ejemplares): ?> 
                <?php $i = 1; ?> 
                <?php foreach ($ejemplares as $ej): ?>
                <tr>
                    <td><span class="fw-bold text-muted"><?= $i++ ?></span></td> <td><?= esc($ej['titulo_libro']) ?></td> <td>
                        <?php 
                            $badge_class = 'bg-secondary';
                            if ($ej['estado'] == 'Disponible') {
                                $badge_class = 'bg-success';
                            } elseif ($ej['estado'] == 'Dañado') {
                                $badge_class = 'bg-warning text-dark';
                            }
                            // Puedes añadir más estados (Prestado, etc.) aquí
                        ?>
                        <span class="badge <?= $badge_class ?>"><?= esc($ej['estado']) ?></span>
                    </td> <td>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('ejemplares/edit/'.$ej['ejemplar_id']) ?>" 
                                class="btn-sm btn-accion-editar">Editar</a>

                            <a href="<?= base_url('ejemplares/delete/'.$ej['ejemplar_id']) ?>" 
                                class="btn-sm btn-accion-eliminar"
                                onclick="return confirm('¿Seguro que quieres eliminar este ejemplar? Esta acción no se puede deshacer.')">Eliminar</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <i class="bi bi-info-circle me-2"></i> No hay ejemplares registrados para este libro.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>

<style>
    .section-title {
        color: #206060;
        font-weight: 700;
        font-size: 1.75rem;
    }
    .clean-table th {
        background-color: #206060;
        color: white;
        border: none;
        vertical-align: middle;
    }
    .clean-table td {
        vertical-align: middle;
        border-top: 1px solid #dee2e6;
    }
    /* Estilos para botones de acción en la tabla (similar a la vista de Libros) */
    .btn-accion-editar {
        color: #206060;
        border: 1px solid #206060;
        background-color: #e6f0f0;
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.85rem;
    }
    .btn-accion-eliminar {
        color: #c75447;
        border: 1px solid #c75447;
        background-color: #f7e6e6;
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.85rem;
    }
</style>

<?= $this->endSection(); ?>