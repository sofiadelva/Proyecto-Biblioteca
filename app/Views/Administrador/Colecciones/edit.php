<?= $this->extend('Plantillas/plantilla_admin') ?>

<?= $this->section('titulo') ?> Editar Colección <?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
    
    <h2 class="section-title mb-4 pb-2 border-bottom">
        <i class="bi bi-pencil-square me-2" style="color: #0C1E44;"></i>
        Gestionar Colección: <?= esc($coleccion['nombre']) ?>
    </h2>

    <?php if(session()->getFlashdata('msg')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('msg') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('colecciones/update/'.$coleccion['coleccion_id']) ?>" method="post" class="row g-3 mb-5">
        <div class="col-md-8">
            <label class="form-label fw-bold">Nombre de la Colección</label>
            <input type="text" name="nombre" class="form-control" value="<?= esc($coleccion['nombre']) ?>" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn text-white w-100 shadow-sm" style="background-color: #0C1E44;">
                <i class="bi bi-arrow-repeat me-1"></i> Actualizar Nombre
            </button>
        </div>
    </form>

    <h5 class="fw-bold mb-3"><i class="bi bi-layers-half me-2"></i> Estructura Interna</h5>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="bg-light text-dark">
                <tr>
                    <th style="width: 30%;">Subgénero</th>
                    <th style="width: 50%;">Subcategorías (clic para editar)</th>
                    <th style="width: 20%;" class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($jerarquia['subgeneros'])): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No hay subgéneros registrados en esta colección.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($jerarquia['subgeneros'] as $sgID => $sg): ?>
                    <tr>
                        <td>
                            <span class="fw-bold text-dark"><?= esc($sg['nombre']) ?></span>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <?php foreach ($sg['subcategorias'] as $sc): ?>
                                    <span class="badge border text-dark fw-normal d-flex align-items-center bg-white p-2" style="font-size: 0.9rem;">
                                        <span class="me-2" style="cursor: pointer;" onclick="editarSubcategoria(<?= $sc['id'] ?>, '<?= esc($sc['nombre']) ?>')" title="Editar nombre">
                                            <?= esc($sc['nombre']) ?>
                                        </span>
                                        <a href="<?= base_url('colecciones/delete_subcat/'.$sc['id']) ?>" 
                                           class="text-danger lh-1" 
                                           onclick="return confirm('¿Eliminar subcategoría?')">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </a>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm text-white border-0" 
                                    style="background-color: #FBB800;" 
                                    onclick="editarSubgenero(<?= $sgID ?>, '<?= esc($sg['nombre']) ?>')" 
                                    title="Editar Subgénero">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <a href="<?= base_url('colecciones/delete_subgen/'.$sgID) ?>" 
                            class="btn btn-sm text-white border-0" 
                            style="background-color: #A01E53;" 
                            onclick="return confirm('¿Desea eliminar este subgénero?')" 
                            title="Eliminar Subgénero">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="<?= base_url('colecciones') ?>" class="btn btn-secondary px-4 shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
    </div>
</div>

<div class="modal fade" id="modalEditSub" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditSub" method="post">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Editar Subgénero</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nuevo nombre del Subgénero</label>
                        <input type="text" name="nombre_sub" id="inputSubNombre" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn text-white" style="background-color: #A01E53;">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditSubCat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <form id="formEditSubCat" method="post">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold">Editar Subcategoría</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="nombre_sc" id="inputSubCatNombre" class="form-control" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-dark w-100 shadow-sm">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Disparador para Subgénero
    function editarSubgenero(id, nombre) {
        document.getElementById('inputSubNombre').value = nombre;
        document.getElementById('formEditSub').action = `<?= base_url('colecciones/update_subgen') ?>/${id}`;
        new bootstrap.Modal(document.getElementById('modalEditSub')).show();
    }

    // Disparador para Subcategoría
    function editarSubcategoria(id, nombre) {
        document.getElementById('inputSubCatNombre').value = nombre;
        document.getElementById('formEditSubCat').action = `<?= base_url('colecciones/update_subcat') ?>/${id}`;
        new bootstrap.Modal(document.getElementById('modalEditSubCat')).show();
    }
</script>

<style>
    .badge { border-radius: 6px; }
    .table th { font-weight: 600; color: #495057; }
    .form-control:focus { border-color: #0C1E44; box-shadow: none; }
</style>

<?= $this->endSection() ?>