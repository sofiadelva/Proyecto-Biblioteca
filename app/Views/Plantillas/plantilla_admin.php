<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>ReadZone - <?php echo $this->renderSection('titulo', 'Panel Principal'); ?></title>
     <link rel="icon" href="<?= base_url('fotos/scj.png') ?>" type="image/png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    
    <?php echo $this->renderSection('head'); ?>
    
    <style>
        /* === ESTILOS PARA PAGINACIÓN CON COLOR #0C1E44 === */
.pagination .page-item.active .page-link {
    background-color: #0C1E44; /* Azul Marino Oscuro */
    border-color: #0C1E44;
    color: #ffffff;
    z-index: 1;
}

.pagination .page-link {
    color: #0C1E44; /* Color del texto del link normal */
    border-color: #e0e0e0; /* Usando un color claro para el borde (asumo --color-border-light) */
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    background-color: #6884BD; /* Color de hover (asumo --color-secondary-blue) */
    border-color: #6884BD;
    color: #ffffff;
}

/* Estilo del link en estado normal (no activo) */
.pagination .page-item:not(.active) .page-link:focus {
    /* Sombra enfocada usando una transparencia del color primario */
    box-shadow: 0 0 0 0.25rem rgba(12, 30, 68, 0.25); 
}

/* Estilo para los botones de Anterior/Siguiente/Primer/Último */
.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    border-radius: 0.25rem;
}
/* === FIN ESTILOS PAGINACIÓN === */
        /* ---------------------------------------------------------------------- */
        /* ESTILOS GLOBALES Y COMPONENTES */
        /* ---------------------------------------------------------------------- */
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
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f8;
            color: #343a40;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background-color: #0C1E44; /* deep teal */
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

        /* Header bar */
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            background: #ffffff;
            border-bottom: 2px solid #ccc;
        }

        /* APLICACIÓN DE LA NUEVA FUENTE AL TÍTULO */
        .header-bar .title {
            font-family: 'beyond_the_mountains', cursive; /* Aplica la fuente personalizada */
            font-size: 2rem; /* Aumentamos el tamaño para fuentes tipo script */
            font-weight: normal; /* Sobrescribimos el 600 ya que las fuentes script suelen ser normales */
            color: #0C1E44;
        }

        /* Modificado para que solo contenga la imagen */
        .header-bar .header-logo {
            display: flex;
            align-items: center;
            /* Eliminamos el gap ya que solo habrá un elemento */
        }

        .header-bar .logo-img {
            height: 50px;
            width: auto;
            border-radius: 8px;
        }
        
        /* Eliminamos el estilo para el span 'Everbook' */
        /*
        .header-bar span {
            font-size: 2rem;
            font-weight: 700;
            color: #0C1E44;
        }
        */

        /* Logout button */
        .logout-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #c75447;
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
        .logout-btn:hover {
            background-color: #a63f39;
        }
        .logout-btn i {
            font-size: 1.2rem;
        }

        /* === [CSS para el estilo de tabla] === */
        .clean-table {
            border-collapse: separate; 
            border-spacing: 0;
            width: 100%;
            background-color: #ffffff;
            border-radius: 10px; 
            overflow: hidden; 
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); 
        }

        .clean-table thead th {
            background-color: #0C1E44; 
            color: #ffffff;
            font-weight: 600;
            padding: 15px 20px;
            border-bottom: none; 
        }

        .clean-table tbody tr {
            transition: background-color 0.2s;
            border-bottom: 1px solid #e9ecef; 
        }

        .clean-table tbody tr:hover {
            background-color: #f4f7f8; 
        }

        .clean-table tbody td {
            padding: 12px 20px;
            vertical-align: middle;
        }

        /* ---------------------------------------------------------------------- */
        /* SOBREESCRITURA DE ESTILOS DE BOTONES DE BOOTSTRAP */
        /* Color deseado: #00ADC6 */
        /* ---------------------------------------------------------------------- */
        
        /* Botón Primario y Secundario */
        .btn-primary,
        .btn-secondary {
            background-color: #00ADC6 !important;
            border-color: #00ADC6 !important;
            color: #ffffff !important;
        }

        /* Efecto Hover para Botones Primario y Secundario */
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active,
        .btn-secondary:hover,
        .btn-secondary:focus,
        .btn-secondary:active {
            background-color: #008fa3 !important; 
            border-color: #008fa3 !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 173, 198, 0.5) !important;
        }
        
        .btn-danger{
            background-color:#A01E53;
        }

        /* ---------------------------------------------------------------------- */
        /* BOTONES DE ACCIÓN (Editar, Eliminar, Info) */
        /* ---------------------------------------------------------------------- */

        /* 1. Botón Editar: color #FBB800 (Amarillo/Naranja) */
        .clean-table .btn-accion-editar {
            background-color: #FBB800 !important; 
            border-color: #FBB800 !important;
            color: #000000 !important;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .clean-table .btn-accion-editar:hover {
            background-color: #d8a200 !important; 
            border-color: #d8a200 !important;
        }

        /* 2. Botón Eliminar: color #A01E53 (Borgoña Oscuro) */
        .clean-table .btn-accion-eliminar {
            background-color: #A01E53 !important; 
            border-color: #A01E53 !important;
            color: #ffffff !important;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .clean-table .btn-accion-eliminar:hover {
            background-color: #841843 !important; 
            border-color: #841843 !important;
        }

        /* 3. Botón Info/Ejemplares (btn-info): color #0C1E44 (Azul Marino Oscuro) */
        .clean-table .btn-info,
        .clean-table .btn-info:focus,
        .clean-table .btn-info:active {
            background-color: #0C1E44 !important; 
            border-color: #0C1E44 !important;
            color: #ffffff !important;
            transition: background-color 0.2s;
        }
        .clean-table .btn-info:hover {
            background-color: #081634 !important;
            border-color: #081634 !important;
        }

        /* Ajustes generales de la tabla */
        .clean-table .btn-sm {
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        .clean-table .d-flex.gap-2 a {
            margin: 0 4px; 
        }


        /* === [OTROS ESTILOS] === */

        .search-bar-container {
            display: flex;
            align-items: center;
            background-color: #0C1E44; 
            border-radius: 8px;
            padding: 2px;
            width: 400px; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .search-bar-container input[type="text"] {
            flex-grow: 1;
            padding: 10px 15px;
            border: none;
            border-radius: 8px 0 0 8px; 
            font-size: 1rem;
            outline: none;
        }

        .search-bar-container .search-icon {
            background-color: #0C1E44; 
            color: #ffffff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 0 8px 8px 0; 
            font-size: 1.2rem;
            transition: background-color 0.3s ease;
            height: 42px; 
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-bar-container .search-icon:hover {
            background-color: #0C1E44;
        }
        
        /* AJUSTE PARA SELECT2 */
        .select2-container .select2-selection--single {
            height: 42px !important; 
            padding: 0.375rem 0.75rem !important;
            border: 1px solid #ced4da !important;
            border-radius: 8px !important;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 24px !important; 
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: 40px !important; 
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            
            <nav class="col-md-2 d-none d-md-block sidebar py-4 position-relative">
                <ul class="nav flex-column" id="sidebarMenu">
                    
                    <li class="nav-item mb-3">
                        <a class="nav-link <?= (uri_string() == 'panel') ? 'active' : '' ?>" 
                            href="<?= base_url('panel'); ?>">
                            <i class="bi bi-house-fill"></i> Home
                        </a>
                    </li>

                    <li class="nav-item mb-3">
                        <a class="nav-link <?= (uri_string() == 'libros') ? 'active' : '' ?>" 
                            href="<?= base_url('libros'); ?>">
                            <i class="bi bi-book-fill"></i> Libros
                        </a>
                    </li>

                    <li class="nav-item mb-3">
                        <a class="nav-link <?= (uri_string() == 'colecciones') ? 'active' : '' ?>" 
                            href="<?= base_url('colecciones'); ?>">
                            <i class="bi bi-card-list"></i> Colecciones
                        </a>
                    </li>

                    <li class="nav-item mb-3">
                        <a class="nav-link <?= (uri_string() === 'gestion_libros') ? 'active' : '' ?>" 
                            href="<?= base_url('gestion_libros'); ?>">
                            <i class="bi bi-book-half"></i> Gestión de Libros
                        </a>
                    </li>

                    <li class="nav-item mb-3">
                        <a class="nav-link <?= (uri_string() == 'transacciones') ? 'active' : '' ?>" 
                            href="<?= base_url('transacciones'); ?>">
                            <i class="bi bi-cash-stack"></i> Transacciones
                        </a>
                    </li>

                    <li class="nav-item mb-3">
                        <a class="nav-link <?= (uri_string() == 'usuarios') ? 'active' : '' ?>" 
                            href="<?= base_url('usuarios'); ?>">
                            <i class="bi bi-person-fill"></i> Usuarios
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'reportes') ? 'active' : '' ?>" 
                            href="<?= base_url('reportes'); ?>">
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

            <main class="col-md-10">
                <div class="header-bar">
                    <div class="title"><?php echo $this->renderSection('titulo'); ?></div>
                    
                    <div class="header-logo">
                        <img src="<?= base_url('fotos/scj.png') ?>" class="logo-img" alt="Logo SCJ" />
                    </div>
                </div>

                <div class="p-4">
                    <?php echo $this->renderSection('contenido'); ?>
                </div>
            </main>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <?php echo $this->renderSection('scripts'); ?>
</body>
</html>