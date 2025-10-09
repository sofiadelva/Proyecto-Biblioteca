<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Everbook - <?= $this->renderSection('titulo', 'Panel Alumno'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    <?= $this->renderSection('head'); ?>
    <style>
        :root {
            --color-primary: #206060;
            --color-secondary-teal: #0f7a7a;
            --color-text-dark: #343a40;
            --color-border-light: #e0e0e0; 
            --color-admin-title: #8b2c2c; /* Se mantiene por si se usa en otros dashboards */
            --color-alumno-title: var(--color-primary); /* Título del alumno en color primario */
            --color-accent-soft: rgba(255, 255, 255, 0.4);
        }
        
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f8; /* Fondo más claro para el contenido principal */
            color: var(--color-text-dark);
        }

        /* Sidebar - Mismos estilos */
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

        /* Header bar (Copied from Bibliotecario) */
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            background: #ffffff;
            border-bottom: 2px solid #ccc;
        }
        .header-bar .title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--color-primary); /* Usamos el color primario para el título del alumno */
        }
        .header-bar .header-logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header-bar .logo-img {
            height: 50px;
            width: auto;
            border-radius: 8px;
        }
        .header-bar span {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-primary);
        }

        /* === [ESTILOS DE TABLA COPIADOS PARA UNIFICAR DISEÑO] === */
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
            background-color: #095959; /* Color verde/teal oscuro para el encabezado */
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
        /* Estilos de Botones de Acción de la tabla (Aunque el alumno no los usa, se copian para integridad) */
        .clean-table .btn-accion-editar, .clean-table .btn-accion-eliminar {
             /* Estilos de los botones omitidos por ser irrelevantes para el alumno, pero se mantiene la clase para evitar errores de CSS */
        }
        /* Estilo de la barra de búsqueda */
        .search-bar-container {
            display: flex;
            align-items: center;
            background-color: #f8f9fa; /* Fondo gris claro */
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 5px 15px;
            max-width: 400px;
            transition: all 0.3s;
        }
        .search-bar-container:focus-within {
            border-color: var(--color-primary); 
            box-shadow: 0 0 5px rgba(32, 96, 96, 0.5); 
        }
        .search-bar-container input {
            border: none;
            outline: none;
            background: transparent;
            flex-grow: 1;
            padding: 5px 0;
            font-size: 1rem;
        }
        .search-bar-container .search-icon {
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding-left: 10px;
        }
        /* === [FIN ESTILOS DE TABLA Y BOTONES] === */
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar py-4 position-relative">
                <ul class="nav flex-column" id="sidebarMenu">
                    <li class="nav-item mb-3">
                        <a class="nav-link <?= (uri_string() === 'alumno/panel') ? 'active' : '' ?>" 
                            href="<?= base_url('alumno/panel'); ?>">
                            <i class="bi bi-house-door-fill"></i> Home
                        </a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link <?= (uri_string() === 'alumno/inventario') ? 'active' : '' ?>" 
                            href="<?= base_url('alumno/inventario'); ?>">
                            <i class="bi bi-journal-bookmark-fill"></i> Inventario
                        </a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link <?= (uri_string() === 'alumno/prestamos') ? 'active' : '' ?>" 
                            href="<?= base_url('alumno/prestamos'); ?>">
                            <i class="bi bi-box-arrow-in-right"></i> Mis Préstamos
                        </a>
                    </li>
                    <hr style="margin: 15px 0; border-color: var(--color-border-light);">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('login/salir') ?>">
                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </nav>
            <main class="col-md-10">
                <div class="header-bar">
                    <div class="title"><?= $this->renderSection('titulo'); ?></div>
                    <div class="header-logo">
                        <img src="<?= base_url('fotos/generated-image.png') ?>" class="logo-img" alt="Logo" />
                        <span>EverBook</span>
                    </div>
                </div>
                <div class="p-4">
                    <?= $this->renderSection('contenido'); ?>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <?= $this->renderSection('scripts'); ?>
</body>
</html>