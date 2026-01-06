<?= $this->extend('Plantillas/plantilla_alumno'); ?>

<?= $this->section('titulo'); ?>Seguridad<?= $this->endSection(); ?>

<?= $this->section('contenido'); ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-white py-3" style="border-radius: 15px 15px 0 0;">
                <h5 class="mb-0 text-dark"><i class="bi bi-key-fill me-2" style="color: var(--color-primary);"></i>Actualizar Contraseña</h5>
            </div>
            <div class="card-body p-4">
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger border-0 small">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success border-0 small">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('alumno/updatePassword') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                            <input type="password" id="pass_nueva" name="pass_nueva" class="form-control border-start-0 border-end-0 bg-light" placeholder="Mínimo 4 caracteres" required>
                            <button class="input-group-text bg-light border-start-0 toggle-password" type="button" data-target="pass_nueva">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">Confirmar Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-check-circle"></i></span>
                            <input type="password" id="pass_confirm" name="pass_confirm" class="form-control border-start-0 border-end-0 bg-light" placeholder="Repita su contraseña" required>
                            <button class="input-group-text bg-light border-start-0 toggle-password" type="button" data-target="pass_confirm">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm" style="background-color: var(--color-primary) !important; border:none; font-size: 1rem;">
                            <i class="bi bi-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Función para alternar visibilidad
    $('.toggle-password').on('click', function() {
        const targetId = $(this).data('target');
        const input = $('#' + targetId);
        const icon = $(this).find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });
});
</script>
<?php $this->endSection(); ?>