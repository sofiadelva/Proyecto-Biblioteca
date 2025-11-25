<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Everbook Dashboard - Bibliotecario</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&display=swap" rel="stylesheet" />

    <style>
    /* Estilo para la fuente 'beyond_the_mountains' - Se requiere para el estilo original del administrador */
    @font-face {
        font-family: 'beyond_the_mountains';
        /* NOTA: En un entorno real, base_url debe funcionar para obtener la ruta */
        src: url('<?= base_url('fonts/beyond_the_mountains.otf') ?>') format('opentype'); 
        font-weight: normal;
        font-style: normal;
        font-display: swap; 
    }
    
    :root {
        /* Definición de la variable para el color de la línea */
        --color-accent-soft: rgba(255, 255, 255, 0.4);
    }

    /* --- ESTILOS GENERALES Y LAYOUT (Original Admin) --- */
    body, html {
        height: 100%;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        /* Fondo del Admin: Azul marino oscuro */
        background-color: #0C1E44;
        color: #343a40;
    }

    .main-panel {
        background-color: #fff;
        min-height: 100vh;
        padding: 2rem 2.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        color: #343a40;
        font-weight: 400;
        overflow-y: auto;
    }

    /* --- SIDEBAR (Original Admin) --- */
    .sidebar {
        min-height: 100vh;
        /* Fondo del Admin: Azul marino oscuro */
        background-color: #0C1E44;
        color: #f8f9fa;
        padding-top: 20px;
    }

    .sidebar .nav-link {
        color: #f8f9fa;
        font-size: 1.1rem;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-radius: 6px;
        transition: background-color 0.3s ease;
        font-weight: 500;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        /* Color de Hover/Activo del Admin: Azul claro brillante */
        background-color: #6884BD;
        color: #ffffff;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }

    .sidebar .nav-link i {
        font-size: 1.4rem;
        min-width: 28px;
        text-align: center;
    }

    /* --- WELCOME CARD (Original Admin) --- */
    .welcome-card {
        /* Fondo del Admin: Cyan/Azul agua */
        background: linear-gradient(135deg, #00ADC6, #00ADC6);
        color: #f8f9fa;
        border-radius: 12px;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-size: 2.8rem;
        /* Usa font-weight: 400 y la fuente 'beyond_the_mountains' */
        font-weight: 400; 
        text-shadow: 0 2px 6px rgba(0,0,0,0.3);
        box-shadow: 0 8px 20px rgba(13,115,115,0.4);
        user-select: none;
        font-family: 'beyond_the_mountains', 'Segoe UI', Tahoma;
    }

    /* --- HEADER LOGO (Original Admin) --- */
    .header-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 180px;
        border-radius: 12px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        gap: 20px;
        color: #206060;
        font-weight: 800;
        font-size: 3rem;
        user-select: none;
        position: relative;
        overflow: hidden; 

        /* Fondo con la imagen de libros del Admin */
        background-image: url('<?= base_url('fotos/fondo logo.png') ?>');
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat;
    }

    .header-logo::before {
        /* Overlay LIGERO para que el contenido de texto/imagen destaque (Admin opacity: 0.85) */
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
    
    .header-logo-content {
        /* Contenedor del contenido, centrado */
        position: relative;
        z-index: 2; 
        display: flex;
        align-items: center; 
        justify-content: center; 
        padding: 0 24px; 
        width: 100%; 
        height: 100%;
    }
    
    /* Imagen superpuesta (SCJ) - Adaptada del admin para este bloque */
    .header-image-overlay {
        max-height: 90%; 
        width: auto;
        filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.4)); 
    }
    /* Ocultamos los estilos no usados del logo original del bibliotecario para evitar conflictos */
    .header-logo .logo-img,
    .header-logo span {
        display: none; 
    }


    /* --- KPI CARDS (Original Admin) --- */

    .dashboard-grid {
        display: grid;
        /* Lo ajustamos a una sola columna si solo hay 2 tarjetas */
        grid-template-columns: 1fr; 
        /* Usamos flex-grow para que se ajuste al alto disponible */
        grid-template-rows: 1fr 1fr; 
        gap: 15px;
        align-items: stretch;
        height: 100%; 
        flex-grow: 1; 
    }
    
    .kpi-card {
        /* Color Primario del Admin: Rojo oscuro/Vino (#A01E53) */
        background: #A01E53 !important;
        color: #ffffff !important;
        border-radius: 12px;
        padding: 15px; 
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
        box-shadow: 0 8px 16px rgba(160, 30, 83, 0.8); 
        background: #8b1947 !important; 
    }

    .kpi-card .kpi-icon {
        font-size: 2.5rem; 
        margin-bottom: 20px; 
        color: #ffffff !important; 
    }

    .kpi-card .kpi-title {
        font-size: 1.1rem; 
        margin: 0;
        font-weight: 600; 
        opacity: 1; 
        line-height: 1.2;
        color: #ffffff !important; 
    }

    /* KPI Secundaria (color-secondary) del Admin: Amarillo/Dorado (#FBB800) */
    .kpi-card.color-secondary {
        /* Usamos el color secundario del Admin */
        background: #FBB800 !important;
        color: #000000 !important;
        box-shadow: 0 4px 10px rgba(251, 184, 0, 0.6);
    }

    .kpi-card.color-secondary:hover {
        box-shadow: 0 8px 16px rgba(251, 184, 0, 0.8);
        background: #da9e00 !important; 
    }
    
    .kpi-card.color-secondary .kpi-icon,
    .kpi-card.color-secondary .kpi-title {
        color: #000000 !important;
    }

    /* Aseguramos que las tarjetas sin clase específica tomen el color primario por defecto */
    .kpi-card:not(.color-secondary) {
        background: #A01E53 !important;
        color: #ffffff !important;
    }
    .kpi-card:not(.color-secondary) .kpi-icon,
    .kpi-card:not(.color-secondary) .kpi-title {
        color: #ffffff !important;
    }


    /* --- LOGOUT BUTTON (Original Admin) --- */
    .logout-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        /* Color del Admin: Azul marino oscuro */
        background: #0C1E44;
        color: #fff;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 30px;
        box-shadow: 0 4px 12px rgba(12, 30, 68, 0.8); 
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        /* Ajustamos la posición para que no interfiera con el nuevo pie de página */
        bottom: 60px; 
    }
    .logout-btn:hover { background-color: #0c1e44; } 
    .logout-btn i { font-size: 1.2rem; }

    /* --- IMAGEN INFERIOR (Tomada del Admin) --- */
    .image-card-section {
        background-image: url('<?= base_url('fotos/ardilla y armadillo.png') ?>'); 
        background-size: contain; 
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 14px;
        box-shadow: 0 8px 28px rgba(13, 115, 115, 0.15);
        flex-grow: 1; 
        min-height: 200px; 
    }
    
    /* --- NUEVO ESTILO: CRÉDITOS --- */
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
</head>
<body>
    <div class="container-fluid" style="height: 100vh; display: flex; flex-direction: row;">
        
        <nav class="col-md-2 d-none d-md-block sidebar py-4">
            <ul class="nav flex-column" id="sidebarMenu">

                <li class="nav-item mb-3">
                    <a class="nav-link active" href="#">
                        <i class="bi bi-house-door-fill"></i> Home
                    </a>
                </li>

                <li class="nav-item mb-3">
                    <a class="nav-link" href="<?= base_url('inventario'); ?>">
                        <i class="bi bi-journal-bookmark-fill"></i> Inventario
                    </a>
                </li>
                
                <li class="nav-item mb-3">
                    <a class="nav-link" href="<?= base_url('gestion_libros'); ?>">
                        <i class="bi bi-book-half"></i> Gestión de Libros
                    </a>
                </li>
                
                <hr style="margin: 15px 0; border-color: var(--color-accent-soft);">

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('login/salir') ?>">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                    </a>
                </li>

            </ul>
        </nav>

        <main class="col-md-10 main-panel px-4 d-flex flex-column">
            
            <div class="row mt-2 mb-3">
                <div class="col-sm-7">
                    <div class="welcome-card shadow">
                        Bienvenido/a<br />
                        <?= session('nombre'); ?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="header-logo shadow">
                        <div class="header-logo-content">
                            <img src="<?= base_url('fotos/scj.png') ?>" class="header-image-overlay" alt="Imagen superpuesta" />
                            <img src="<?= base_url('fotos/generated-image.png') ?>" class="logo-img" alt="Logo" style="display: none;" />
                            <span style="display: none;">EverBook</span>
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
                        
                        <a href="<?= base_url('inventario') ?>" class="kpi-card color-primary">
                            <i class="bi bi-journals kpi-icon"></i>
                            <p class="kpi-title">Ver Inventario Completo</p>
                        </a>

                        <a href="<?= base_url('gestion_libros') ?>" class="kpi-card color-secondary">
                            <i class="bi bi-pencil-square kpi-icon"></i>
                            <p class="kpi-title">Agrega Préstamos y Devoluciones</p>
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
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>