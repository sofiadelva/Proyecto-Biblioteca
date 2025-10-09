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
        /* Estilos para la tarjeta de bienvenida (fondo verde) */
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
    
    /* === ELIMINACIÓN DE CÓDIGO DUPLICADO ===
       Se ha quitado todo el CSS de .header-logo y .header-logo-content, 
       ya que la plantilla base ya dibuja el encabezado (header-bar).
       Creamos un contenedor simple para el logo Everbook si lo necesitas en el dashboard. 
    */
    .everbook-logo-card {
        height: 180px;
        border-radius: 12px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden; 
        /* Usamos la imagen de fondo con transparencia */
        background-image: url('<?= base_url('fotos/libros.png') ?>');
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat;
    }
    .everbook-logo-card::before {
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
        color: #206060;
        text-shadow: 0 1px 3px rgba(255, 255, 255, 0.5);
    }
    
    /* Acerca de Nosotros - Mismo estilo en ambos */
    .about-section {
        background: linear-gradient(90deg, #9c443a, #c75447);
        color: #fff;
        padding: 16px 32px;
        font-size: 1.8rem;
        font-weight: 600;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(201, 75, 61, 0.65);
    }

    /* Contenedor del contenido "Acerca de Nosotros" */
    .hero-section-container {
        background-color: #ffffff;
        box-shadow: 0 8px 28px rgba(13, 115, 115, 0.15);
        border-radius: 14px;
        flex-grow: 1; 
    }
    
    .hero-section {
        display: flex;
        flex-direction: column;
        gap: 20px;
        padding: 40px 32px;
        align-items: center; 
        color: #343a40;
    }

    .hero-text { 
        text-align: center; 
        margin-bottom: 0;
        max-width: 800px; 
    }

    .hero-title {
        font-size: 2.8rem;
        margin-bottom: 24px;
        font-weight: 700;
        color: #8b2c2c;
        font-family: 'Georgia', serif;
    }

    .hero-paragraph {
        font-size: 1.18rem;
        line-height: 1.7;
        max-width: 700px;
        margin: 0 auto;
    }
    
    /* --- Estilos para los Cuadros Dinámicos (KPI Cards) --- */
    .dashboard-grid {
        /* Cambiamos a 2 filas, 1 columna o la estructura que necesites. 
           Si solo son 2 tarjetas, una debajo de la otra es más limpio para este espacio vertical. 
           Mantenemos la cuadrícula 2x2, pero las celdas vacías se quedan sin contenido.
        */
        display: grid;
        grid-template-columns: 1fr; /* Una sola columna */
        grid-template-rows: 1fr 1fr; /* Dos filas, cada una con una tarjeta */
        gap: 15px;
        align-items: stretch;
        height: 100%; 
        flex-grow: 1; 
    }
    
    .kpi-card {
        /* Estilos base de las tarjetas KPI (verde/teal) */
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
        /* Estilos de color secundario (verde más claro) */
        background: linear-gradient(135deg, #3a9c7b, #47c787);
        box-shadow: 0 4px 10px rgba(58, 156, 123, 0.5);
    }
    .kpi-card.color-secondary:hover {
        box-shadow: 0 8px 16px rgba(58, 156, 123, 0.8);
        background: linear-gradient(135deg, #47c787, #3a9c7b);
    }

    /* Ocultamos el botón flotante de Cerrar Sesión, ya que está en el sidebar y en el KPI */
    .logout-btn {
        display: none !important;
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
                <img src="<?= base_url('fotos/generated-image.png') ?>" class="logo-img" alt="Logo" />
                <span>EverBook</span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2 mb-3 flex-grow-1 d-flex align-items-stretch">
    
    <div class="col-lg-7 d-flex flex-column gap-3">
        <div class="about-section">Acerca de Nosotros</div>
        
        <div class="hero-section-container">
            <div class="hero-section">
                <div class="hero-text">
                    <h1 class="hero-title">Bienvenido a Everbook</h1>
                    <p class="hero-paragraph">
                        En Everbook, creemos que el conocimiento es la llave que abre todas las puertas hacia el éxito. 
                        Como estudiante, tienes acceso a una amplia colección de libros y recursos digitales 
                        diseñados para inspirar y apoyar tu crecimiento académico. Usa los accesos rápidos 
                        para consultar nuestro inventario o revisar el estado de tus préstamos.
                    </p>
                </div>
            </div>
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
 <button class="btn logout-btn" type="button" onclick="window.location.href='<?= base_url('login/salir') ?>'"> 
                Cerrar Sesión <i class="bi bi-box-arrow-right"></i>
            </button>
<?php 
$this->endSection(); 
?>