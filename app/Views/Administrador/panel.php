<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Everbook Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&display=swap" rel="stylesheet" />

    <style>
        :root {
            /* Definición de la variable para el color de la línea */
            --color-accent-soft: rgba(255, 255, 255, 0.4);
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0a3f44;
            color: #343a40;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #095959;
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
            background-color: #0f7a7a;
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
        
        .header-logo {
            display: flex;
            align-items: center;
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

            /* Fondo con la imagen de libros y sin transparencia */
            background-image: url('<?= base_url('fotos/libros.png') ?>');
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat;
        }

        .header-logo::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #f5f5f5; 
            opacity: 0.85; /* Overlay blanco para dar transparencia al fondo */
            z-index: 1;
        }
        
        .header-logo-content {
            position: relative;
            z-index: 2; 
            display: flex;
            align-items: center;
            padding: 0 24px; 
            gap: 20px;
            width: 100%; 
        }
        
        .header-logo .logo-img {
            height: 80px;
            width: auto;
            border-radius: 8px;
            filter: drop-shadow(0 0 2px rgba(0,0,0,0.15));
        }

        .header-logo span {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            font-weight: 700;
            font-size: 3rem;
            color: #206060;
            text-shadow: 0 1px 3px rgba(255, 255, 255, 0.5);
        }

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
            background: #c75447;
            color: #fff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 30px;
            box-shadow: 0 4px 12px rgba(199, 84, 71, 0.8);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .logout-btn:hover { background-color: #a63f39; }
        .logout-btn i { font-size: 1.2rem; }
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
                                    Nuestra biblioteca está dedicada a estudiantes y maestros, ofreciendo un espacio acogedor y 
                                    moderno donde la curiosidad y el aprendizaje se encuentran. Con una amplia colección de libros, 
                                    recursos digitales y actividades diseñadas para inspirar y apoyar tu crecimiento académico, Everbook 
                                    es mucho más que una biblioteca: es tu aliado en cada etapa del aprendizaje. Ven y descubre un mundo de 
                                    posibilidades, porque en Everbook, tu educación comienza aquí.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 d-flex flex-column">
                    <div class="dashboard-grid">
                        
                        <a href="<?= base_url('libros') ?>" class="kpi-card">
                            <i class="bi bi-journals kpi-icon"></i>
                            <p class="kpi-title">Libros en Inventario</p>
                        </a>

                        <a href="<?= base_url('usuarios') ?>" class="kpi-card color-secondary">
                            <i class="bi bi-people-fill kpi-icon"></i>
                            <p class="kpi-title">Usuarios Activos</p>
                        </a>

                        <a href="<?= base_url('transacciones') ?>" class="kpi-card">
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

            <button class="btn logout-btn" type="button" onclick="window.location.href='<?= base_url('login/salir') ?>'"> 
                Cerrar Sesión <i class="bi bi-box-arrow-right"></i>
            </button>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>