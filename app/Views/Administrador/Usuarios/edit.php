<?php 
// Extiende de la plantilla principal
echo $this->extend('Plantillas/plantilla_admin'); 
?>

<?php 
// Define la sección "titulo"
$this->section('titulo'); 
?>
Editar Usuario
<?php 
$this->endSection(); 
?>

<?php 
// Abre la sección "contenido"
$this->section('contenido'); 
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-0 mb-4 p-4" style="border-radius: 12px;">
            
            <h2 class="section-title mb-4 pb-2 border-bottom">
                <i class="bi bi-person-fill me-2" style="color: #0C1E44;"></i>
                Editar Usuario: <?= esc($usuario['nombre']); ?>
            </h2>
            
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach(session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('usuarios/update/'.$usuario['usuario_id']); ?>" class="row g-4" autocomplete="off">
                
                <div class="col-md-6">
                    <label for="nombre" class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" class="form-control" 
                           value="<?= old('nombre') ?? esc($usuario['nombre']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="carne" class="form-label fw-bold">Carné <span class="text-danger">*</span></label>
                    <input type="number" name="carne" id="carne" class="form-control" 
                           value="<?= old('carne') ?? esc($usuario['carne']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="correo" class="form-label fw-bold">Correo <span class="text-danger">*</span></label>
                    <input type="email" name="correo" id="correo" class="form-control" 
                           value="<?= old('correo') ?? esc($usuario['correo']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="rol" class="form-label fw-bold">Rol <span class="text-danger">*</span></label>
                    <select class="form-select" name="rol" id="rol" required>
                        <option value="">Seleccionar Rol</option>
                        
                        <?php 
                        // Array de roles lógicos
                        $roles = ['Administrador', 'Bibliotecario', 'Alumno', 'Maestro'];
                        $current_rol = old('rol') ?? $usuario['rol'];
                        
                        foreach ($roles as $rol): ?>
                            <option value="<?= esc($rol); ?>" <?= ($current_rol == $rol) ? 'selected' : ''; ?>>
                                <?= esc($rol); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label fw-bold">Contraseña (dejar vacío para no cambiar)</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <small class="form-text text-muted">Solo ingrese una contraseña si desea actualizarla.</small>
                </div>
                
                <div class="col-12 mt-5 d-flex justify-content-start gap-3">
                    <a href="<?= base_url('usuarios'); ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
                        <i class="bi bi-arrow-left-short"></i> Regresar
                    </a>
                    <button type="submit" class="btn text-white px-4 py-2 shadow" style="background-color:#A01E53
;">
                        <i class="bi bi-arrow-repeat me-2"></i> Actualizar Usuario
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    .section-title {
        color: #0C1E44;
        font-weight: 700;
        font-size: 1.75rem;
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 15px;
        box-shadow: none !important;
        border: 1px solid #ced4da;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        transition: background-color 0.2s;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
</style>

<?php 
$this->endSection(); 
?>