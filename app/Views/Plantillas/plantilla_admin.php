<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Everbook Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <style>
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
      background-color: #095959; /* deep teal */
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

    /* Header bar */
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
      color: #8b2c2c;
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
      color: #206060;
    }

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
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      
      <!-- Sidebar -->
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
      <a class="nav-link <?= (uri_string() == 'categorias') ? 'active' : '' ?>" 
         href="<?= base_url('categorias'); ?>">
        <i class="bi bi-card-list"></i> Categorías
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

  </ul>
</nav>

      <!-- Main Content -->
      <main class="col-md-10">
        <!-- Barra superior -->
        <div class="header-bar">
          <div class="title"><?php echo $this->renderSection('titulo'); ?></div>
          <div class="header-logo">
            <img src=<?= base_url('fotos/generated-image.png') ?> class="logo-img" alt="Logo" />
            <span>EverBook</span>
          </div>
        </div>

        <!-- Contenido dinámico -->
        <div class="p-4">
          <?php echo $this->renderSection('contenido'); ?>
        </div>
      </main>
    </div>
  </div>

  

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
