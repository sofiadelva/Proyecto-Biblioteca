<?php 
// Extiende de la plantilla principal del alumno
echo $this->extend('Plantillas/plantilla_alumno'); 
?>


<?php 
// Abre la sección "contenido"
$this->section('contenido'); 
?>

<style>
    /* Estilos específicos de esta vista (Dashboard) */
    .welcome-card {
        /* Estilos para la tarjeta de bienvenida (fondo verde/teal) */
        background: linear-gradient(135deg, #206060, #0d7373);
        color: #f8f9fa;
        border-radius: 12px;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-size: 2.8rem;
        font-weight: 700;
        text-shadow: 0 2px 6px rgba(0,0,0,0.3);
        box-shadow: 0 8px 20px rgba(13,115,115,0.4);
        user-select: none;
    }
    
    /* --- LOGO EVERBOOK (Adaptado de Bibliotecario a Alumno) --- */
    .everbook-logo-card {
        height: 180px;
        border-radius: 12px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden; 
        /* Fondo con la imagen de libros del Alumno */
        background-image: url('<?= base_url('fotos/libros.png') ?>');
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat;
    }
    .everbook-logo-card::before {
        /* Overlay LIGERO para que el contenido de texto/imagen destaque (Mismo opacity: 0.85) */
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #f5f5f5; 
        opacity: 0.85; 
        z-index: 1;
    }
    .everbook-logo-content {
        position: relative;
        z-index: 2; 
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .everbook-logo-content img {
        height: 80px;
        width: auto;
        border-radius: 8px;
        filter: drop-shadow(0 0 2px rgba(0,0,0,0.15));
    }
    .everbook-logo-content span {
        font-weight: 700;
        font-size: 3rem;
        /* Color principal de Everbook */
        color: #206060; 
        text-shadow: 0 1px 3px rgba(255, 255, 255, 0.5);
    }


    /* --- IMAGEN INFERIOR (Restaurada de Bibliotecario) --- */
    .image-card-section {
        /* Usamos la imagen de ardilla y armadillo original */
        background-image: url('<?= base_url('fotos/ardilla y armadillo.png') ?>'); 
        background-size: contain; /* 'contain' para asegurar que la imagen completa sea visible */
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 14px;
        box-shadow: 0 8px 28px rgba(13, 115, 115, 0.15);
        flex-grow: 1; 
        min-height: 200px; 
    }

    /* --- KPI CARDS (Adaptado a Alumno) --- */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr; 
        grid-template-rows: 1fr 1fr; 
        gap: 15px;
        align-items: stretch;
        height: 100%; 
        flex-grow: 1; 
    }
    
    .kpi-card {
        /* Color Primario del Alumno: Verde/Teal oscuro */
        background: linear-gradient(135deg, #206060, #0d7373);
        color: #fff;
        border-radius: 12px;
        padding: 20px; 
        text-align: left;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        transition: transform 0.2s, box-shadow 0.2s;
        text-decoration: none; 
        display: flex;
        flex-direction: column;
        justify-content: flex-end; 
    }
    
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(13, 115, 115, 0.6);
        background: linear-gradient(135deg, #0d7373, #206060);
    }

    .kpi-card .kpi-icon {
        font-size: 2.5rem; 
        margin-bottom: 20px; 
        color: #f8f9fa;
    }

    .kpi-card .kpi-title {
        font-size: 1.1rem; 
        margin: 0;
        font-weight: 600; 
        opacity: 1; 
        line-height: 1.2;
    }
    
    .kpi-card.color-secondary {
        /* Color Secundario del Alumno: Verde más claro */
        background: linear-gradient(135deg, #3a9c7b, #47c787);
        box-shadow: 0 4px 10px rgba(58, 156, 123, 0.5);
    }
    .kpi-card.color-secondary:hover {
        box-shadow: 0 8px 16px rgba(58, 156, 123, 0.8);
        background: linear-gradient(135deg, #47c787, #3a9c7b);
    }

    /* Ocultamos el botón flotante de Cerrar Sesión, ya que se asume que está en el sidebar */
    .logout-btn {
        display: none !important;
    }
    
    /* --- NUEVO ESTILO: CRÉDITOS (Restaurado de Bibliotecario) --- */
    .footer-credits {
        margin-top: auto; /* Empuja el footer hacia abajo */
        padding-top: 20px;
        padding-bottom: 5px;
        text-align: center;
        font-size: 0.85rem;
        color: #6c757d; /* Gris sutil */
        border-top: 1px solid #e9ecef; /* Línea separadora */
    }
</style>

<div class="row mt-2 mb-3">
    <div class="col-sm-7">
        <div class="welcome-card shadow">
            Bienvenido/a<br />
            <?= session('nombre'); ?>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="everbook-logo-card shadow">
            <div class="everbook-logo-content">
                <img src="<?= base_url('fotos/generated-image.png') ?>" class="logo-img" alt="Logo EverBook" />
                <span>EverBook</span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2 mb-3 flex-grow-1 d-flex align-items-stretch">
    
        <div class="col-lg-7 d-flex flex-column">
                <div class="image-card-section">
        </div>
    </div>

        <div class="col-lg-5 d-flex flex-column">
        <div class="dashboard-grid">
                        <a href="<?= base_url('alumno/inventario') ?>" class="kpi-card">
                <i class="bi bi-journals kpi-icon"></i>
                <p class="kpi-title">Explorar Inventario Completo</p>
            </a>

                        <a href="<?= base_url('alumno/prestamos') ?>" class="kpi-card color-secondary">
                <i class="bi bi-box-arrow-in-right kpi-icon"></i>
                <p class="kpi-title">Ver el Estado de Mis Préstamos</p>
            </a>
        </div>
    </div>
     
</div>

<div class="footer-credits">
    Página realizada por: Sofía del Valle Ajosal y Emily Abril Santizo Urízar
</div>

 <button class="btn logout-btn" type="button" onclick="window.location.href='<?= base_url('login/salir') ?>'"> 
                Cerrar Sesión <i class="bi bi-box-arrow-right"></i>
            </button>
<?php 
$this->endSection(); 
?>