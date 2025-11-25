<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>ReadZone - Home</title>
     <link rel="icon" href="<?= base_url('fotos/scj.png') ?>" type="image/png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&display=swap" rel="stylesheet" />

    <style>
        @font-face {
        /* Usa el nombre que quieras para referirte a esta fuente en el CSS */
        font-family: 'beyond_the_mountains';
        /* Ruta a tu archivo OTF en la carpeta public/fonts/ */
        src: url('<?= base_url('fonts/beyond_the_mountains.otf') ?>') format('opentype'); 
        font-weight: normal;
        font-style: normal;
        /* Recomendado: Muestra el texto inmediatamente mientras carga la fuente */
        font-display: swap; 
    }
        :root {
            /* Definición de la variable para el color de la línea */
            --color-accent-soft: rgba(255, 255, 255, 0.4);
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0C1E44;
            color: #343a40;
        }

        .sidebar {
            min-height: 100vh;
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

        .welcome-card {
            background: linear-gradient(135deg, #00ADC6, #00ADC6);
            color: #f8f9fa;
            border-radius: 12px;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-size: 2.8rem;
            font-weight: 400;
            text-shadow: 0 2px 6px rgba(0,0,0,0.3);
            box-shadow: 0 8px 20px rgba(13,115,115,0.4);
            user-select: none;
            font-family: 'beyond_the_mountains', 'Segoe UI', Tahoma;
        }
        
        /* MODIFICADO: Contenedor para la imagen de fondo y la superpuesta */
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

            /* Fondo con la imagen de libros */
            background-image: url('<?= base_url('fotos/fondo logo.png') ?>');
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat;
        }

        .header-logo::before {
            /* Overlay LIGERO para que la imagen superpuesta destaque */
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #f5f5f5; 
            opacity: 0.85; /* VALOR AJUSTADO: Fondo más transparente (más claro) */
            z-index: 1;
        }
        
        .header-logo-content {
            /* Contenedor de la imagen superpuesta, centrado */
            position: relative;
            z-index: 2; 
            display: flex;
            align-items: center; /* Centrado vertical */
            justify-content: center; /* Centrado horizontal */
            padding: 0 24px; 
            width: 100%; 
            height: 100%;
        }
        
        /* NUEVO: Estilo para la imagen superpuesta */
        .header-image-overlay {
            max-height: 90%; 
            width: auto;
            filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.4)); 
        }

        /* Ocultamos el viejo logo y texto */
        .header-logo .logo-img,
        .header-logo span {
            display: none; 
        }


        .about-section {
            background: linear-gradient(90deg, #0C1E44, #0C1E44);
            color: #fff;
            padding: 16px 32px;
            font-size: 1.8rem;
            font-weight: 600;
            border-radius: 12px;
        }
        
        /* Contenedor del contenido "Acerca de Nosotros" */
        .hero-section-container {
              background-color: #ffffff;
              box-shadow: 0 8px 28px rgba(13, 115, 115, 0.15);
              border-radius: 14px;
              /* Aseguramos que crezca para usar todo el alto de la columna */
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
            margin-bottom: 0; /* No hay imagen debajo, eliminamos el margen */
            max-width: 800px; 
        }

        .hero-title {
            font-size: 2.8rem;
            margin-bottom: 24px;
            font-weight: 700;
            color: #0C1E44;
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
            display: grid;
            grid-template-columns: 1fr 1fr; 
            grid-template-rows: 1fr 1fr; /* 2 filas de igual altura para los 4 cuadros */
            gap: 15px;
            align-items: stretch;
            height: 100%; 
            flex-grow: 1; 
        }
        
        .kpi-card {
            background: linear-gradient(135deg, #206060, #0d7373);
            color: #fff;
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
        
        .col-lg-5 {
            display: flex;
            flex-direction: column;
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
            /* AJUSTE: Tamaño de letra aumentado para visibilidad */
            font-size: 1.1rem; 
            margin: 0;
            font-weight: 600; 
            opacity: 1; 
            line-height: 1.2;
        }

        .kpi-card.color-secondary {
              background: linear-gradient(135deg, #3a9c7b, #47c787);
              box-shadow: 0 4px 10px rgba(58, 156, 123, 0.5);
        }
        .kpi-card.color-secondary:hover {
            box-shadow: 0 8px 16px rgba(58, 156, 123, 0.8);
            background: linear-gradient(135deg, #47c787, #3a9c7b);
        }


        .logout-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #0C1E44;
            color: #fff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .logout-btn:hover { background-color: #0C1E44; }
        .logout-btn i { font-size: 1.2rem; }

        /* --- INICIO CÓDIGO CORREGIDO PARA KPI CARDS --- */
        
        /* Color para el primer y tercer KPI (#A01E53) */
        .kpi-card.color-primary {
            /* USAMOS 'background' para sobrescribir el linear-gradient */
            background: #A01E53 !important;
            color: #ffffff !important; 
        }

        .kpi-card.color-primary .kpi-icon,
        .kpi-card.color-primary .kpi-title {
            color: #ffffff !important;
        }

        /* Color para el segundo y cuarto KPI (#FBB800) */
        .kpi-card.color-secondary {
            /* USAMOS 'background' para sobrescribir el linear-gradient */
            background: #FBB800 !important;
            color: #000000 !important; /* Negro para mejor contraste con el amarillo */
        }

        .kpi-card.color-secondary .kpi-icon,
        .kpi-card.color-secondary .kpi-title {
            color: #000000 !important;
        }

        /* --- Estilo para la tarjeta de imagen inferior --- */

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
                        <i class="bi bi-house-fill"></i> Home
                    </a>
                </li>

                <li class="nav-item mb-3">
                    <a class="nav-link" href="<?= base_url('libros'); ?>">
                        <i class="bi bi-book-fill"></i> Libros
                    </a>
                </li>

                <li class="nav-item mb-3">
                    <a class="nav-link" href="<?= base_url('categorias'); ?>">
                        <i class="bi bi-card-list"></i> Categorías
                    </a>
                </li>

                <li class="nav-item mb-3">
                    <a class="nav-link" href="<?= base_url('transacciones'); ?>">
                        <i class="bi bi-cash-stack"></i> Transacciones
                    </a>
                </li>

                <li class="nav-item mb-3">
                    <a class="nav-link" href="<?= base_url('usuarios'); ?>">
                        <i class="bi bi-person-fill"></i> Usuarios
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('reportes'); ?>">
                        <i class="bi bi-bar-chart-fill"></i> Reportes
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
                        <?php echo session('nombre'); ?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="header-logo shadow">
                        <div class="header-logo-content">
                            <img src="<?= base_url('fotos/scj.png') ?>" class="header-image-overlay" alt="Imagen superpuesta" />
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
                        
                        <a href="<?= base_url('libros') ?>" class="kpi-card color-primary"> 
                            <i class="bi bi-journals kpi-icon"></i>
                            <p class="kpi-title">Libros en Inventario</p>
                        </a>

                        <a href="<?= base_url('usuarios') ?>" class="kpi-card color-secondary">
                            <i class="bi bi-people-fill kpi-icon"></i>
                            <p class="kpi-title">Usuarios Activos</p>
                        </a>

                        <a href="<?= base_url('transacciones') ?>" class="kpi-card color-primary"> 
                            <i class="bi bi-box-arrow-in-up-right kpi-icon"></i>
                            <p class="kpi-title">Préstamos Activos</p>
                        </a>
                                                
                        <a href="<?= base_url('categorias') ?>" class="kpi-card color-secondary">
                            <i class="bi bi-tags-fill kpi-icon"></i>
                            <p class="kpi-title">Total de Categorías</p>
                        </a>
                        
                    </div>
                </div>

                
            </div>
            <div class="footer-credits">
                Página realizada por: Sofía del Valle Ajosal y Emily Abril Santizo Urízar - Promo 2025
            </div>

            <button class="btn logout-btn" type="button" onclick="window.location.href='<?= base_url('login/salir') ?>'"> 
                Cerrar Sesión <i class="bi bi-box-arrow-right"></i>
            </button>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>