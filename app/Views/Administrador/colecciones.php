<?= $this->extend('Plantillas/plantilla_admin') ?>

<?= $this->section('titulo') ?> 
Colecciones de Biblioteca 
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="<?= base_url('colecciones/create'); ?>" class="btn btn-lg text-white shadow" style="background-color:#0C1E44;">
        <i class="bi bi-plus-circle-fill me-2"></i>Agregar Nueva Colección
    </a>

    <a href="<?= base_url('colecciones/nuevo_subgenero'); ?>" class="btn btn-lg text-white shadow" style="background-color:#0C1E44;">
        <i class="bi bi-plus-circle-fill me-2"></i>Agregar Subgéneros
    </a>

    <a href="<?= base_url('colecciones/nueva_subcategoria'); ?>" class="btn btn-lg text-white shadow" style="background-color:#0C1E44;">
        <i class="bi bi-plus-circle-fill me-2"></i>Agregar Subcategorías
    </a>

    
    <form method="get" action="<?= base_url('colecciones'); ?>" class="search-bar-container">
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar colección, subgénero..." 
            value="<?= esc($buscar ?? '') ?>" 
        />
        <button type="submit" class="search-icon">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>

<div class="hierarchy-container shadow-sm bg-white p-4" style="border-radius: 12px;">
   

    <?php if (empty($jerarquia)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-info-circle me-2"></i>No se encontraron colecciones registradas.
        </div>
    <?php else: ?>
        <div class="accordion accordion-flush" id="accordionColecciones">
            <?php foreach ($jerarquia as $cID => $coleccion): ?>
                <div class="accordion-item border mb-3 rounded shadow-sm">
                    
                    <div class="accordion-header d-flex align-items-center  rounded-top p-2">
                        <button class="accordion-button collapsed bg-transparent shadow-none text-dark p-0 ps-3" 
                                type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapseC<?= $cID ?>"
                                style="font-family: inherit;"> <span style="font-weight: 700; font-size: 1.1rem; color: #0C1E44;">
                                <?= esc($coleccion['nombre']) ?>
                            </span>

                            <span class="badge ms-3 rounded-pill text-white" style="background-color: #6c757d; font-size: 0.7rem; font-weight: 400;">
                                <?= count($coleccion['subgeneros']) ?> subgéneros
                            </span>
                        </button>
                        
                        <div class="px-3 d-flex gap-2">
                            <a href="<?= base_url('colecciones/edit/'.$cID); ?>" class="btn-sm btn-accion-editar text-decoration-none">
                                <i class="bi"></i> Editar
                            </a>
                            <a href="<?= base_url('colecciones/delete/'.$cID); ?>" class="btn-sm btn-accion-eliminar text-decoration-none"
                               onclick="return confirm('¿Seguro que quieres eliminar esta colección?')">
                                <i class="bi"></i> Eliminar
                            </a>
                        </div>
                    </div>

                    <div id="collapseC<?= $cID ?>" class="accordion-collapse collapse" data-bs-parent="#accordionColecciones">
                        <div class="accordion-body bg-white">
                            <?php if (empty($coleccion['subgeneros'])): ?>
                                <small class="text-muted italic px-4">Sin subgéneros registrados.</small>
                            <?php else: ?>
                                <div class="accordion accordion-flush ms-4 border-start border-2" id="accordionSubg<?= $cID ?>">
                                    <?php foreach ($coleccion['subgeneros'] as $sGID => $subgenero): ?>
                                        <div class="accordion-item border-0">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed py-2  shadow-none bg-transparent ps-3" 
                                                        type="button" data-bs-toggle="collapse" 
                                                        data-bs-target="#collapseSG<?= $sGID ?>">
                                                    <i class="bi bi-bookmark-fill me-2 text-info"></i>
                                                    <?= esc($subgenero['nombre']) ?>
                                                </button>
                                            </h2>
                                            
                                            <div id="collapseSG<?= $sGID ?>" class="accordion-collapse collapse" data-bs-parent="#accordionSubg<?= $cID ?>">
                                                <div class="accordion-body py-1 ms-4 border-start">
                                                    <?php if (empty($subgenero['subcategorias'])): ?>
                                                        <span class="text-muted small">Sin subcategorías.</span>
                                                    <?php else: ?>
                                                        <?php foreach ($subgenero['subcategorias'] as $subcat): ?>
                                                            <div class="py-1" style="font-size: 0.9rem;">
                                                                <i class="bi bi-dot me-1"></i> <?= esc($subcat['nombre']) ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #0C1E44;
    }
    
   .btn-accion-editar {
            background-color: #FBB800 !important; 
            border-color: #FBB800 !important;
            color: #000000 !important;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.2s;
        }
         .btn-accion-editar:hover {
            background-color: #d8a200 !important; 
            border-color: #d8a200 !important;
        }

        /* 2. Botón Eliminar: color #A01E53 (Borgoña Oscuro) */
        .btn-accion-eliminar {
            background-color: #A01E53 !important; 
            border-color: #A01E53 !important;
            color: #ffffff !important;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .btn-accion-eliminar:hover {
            background-color: #841843 !important; 
            border-color: #841843 !important;
        }

    /* Ajuste para que la línea de nivel 2 y 3 no sea tan larga */
    .border-start {
        border-color: #dee2e6 !important;
    }
</style>

<?php $this->endSection(); ?>