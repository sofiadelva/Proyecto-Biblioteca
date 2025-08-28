<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Everbook Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <style>
    /* Reset and base */
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
      background-color: #0e4c51; /* deep teal */
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

    /* Main content */
    .main-panel {
      background-color: #ffffff;
      min-height: 100vh;
      padding: 2rem 2.5rem;
      position: relative;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    /* Welcome card */
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

    /* Header logo */
    .header-logo {
      display: flex;
      align-items: center;
      height: 180px;
      background: #f5f5f5;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
      padding: 0 24px;
      gap: 20px;
    }

    .header-logo .logo-img {
      height: 80px;
      width: auto;
      border-radius: 8px;
      filter: drop-shadow(0 0 2px rgba(0,0,0,0.15));
    }

    .header-logo span {
      font-size: 3rem;
      font-weight: 800;
      color: #206060;
      user-select: none;
    }

    /* About section header */
    .about-section {
      background: linear-gradient(90deg, #9c443a, #c75447);
      color: #fff;
      padding: 16px 32px;
      font-size: 1.8rem;
      font-weight: 600;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(201, 75, 61, 0.65);
      user-select: none;
    }

    /* Hero section */
    .hero-section {
      display: flex;
      gap: 40px;
      padding: 40px 32px;
      background-color: #ffffff;
      box-shadow: 0 8px 28px rgba(13, 115, 115, 0.15);
      border-radius: 14px;
      align-items: center;
    }
    @media (max-width: 768px) {
      .hero-section {
        flex-direction: column;
        text-align: center;
      }
    }

    .hero-img {
      max-width: 350px;
      width: 100%;
      border-radius: 14px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
      user-select: none;
    }

    .hero-text {
      flex: 1;
      text-align: left;
      color: #324a4a;
    }

    .hero-title {
      font-size: 2.8rem;
      margin-bottom: 24px;
      font-weight: 700;
      color: #8b2c2c;
      font-family: 'Georgia', serif;
      user-select: none;
    }

    .hero-paragraph {
      font-size: 1.18rem;
      line-height: 1.7;
      color: #4a4a4a;
      max-width: 700px;
      user-select: none;
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
      box-shadow: 0 4px 12px rgba(199, 84, 71, 0.8);
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
      user-select: none;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .logout-btn:hover {
      background-color: #a63f39;
      box-shadow: 0 6px 16px rgba(166, 63, 57, 0.9);
    }
    .logout-btn i {
      font-size: 1.2rem;
    }
  </style>
</head>
<body>
  <div class="container-fluid" style="flex: 1 0 auto;">
    <div class="row" style="height: 100%;">
      <!-- Sidebar -->
      <nav class="col-md-2 d-none d-md-block sidebar py-4 position-relative">
        <div class="mb-4">
          <ul class="nav flex-column">
            <li class="nav-item mb-3">
              <a class="nav-link active" href="#"><i class="bi bi-house-door-fill"></i> Home</a>
            </li>
            <li class="nav-item mb-3">
              <a class="nav-link <?= (uri_string() === 'alumno/inventario') ? 'active' : '' ?>" 
   href="<?= base_url('alumno/inventario'); ?>">
   <i class="bi bi-journal-bookmark-fill"></i> Inventario
</a>

            </li>
            <li class="nav-item mb-3">
              <a class="nav-link <?= (uri_string() === 'alumno/inventario') ? 'active' : '' ?>" 
               href="<?= base_url('prestamos'); ?>"><i class="bi bi-box-arrow-in-right"></i> Préstamos</a>
            </li>
          </ul>
        </div>
      </nav>
      <!-- Main Content -->
      <main class="col-md-10 main-panel px-4">
        <div class="row mt-2 mb-3">
          <div class="col-sm-5">
            <div class="welcome-card shadow">
              Bienvenido/a<br /><?= session('nombre') ?>
            </div>
          </div>
          <div class="col-sm-7">
            <div class="header-logo shadow">
              <img src="<?= base_url('fotos/generated-image.png') ?>" class="logo-img" alt="Logo" />
              <span>EverBook</span>
            </div>
          </div>
        </div>
        <div class="about-section mt-2 mb-2">Acerca de Nosotros</div>
        <div class="bg-white p-4">
          <div class="hero-section">
            <img src="<?= base_url('fotos/libros.png') ?>" alt="Estantería de libros en Everbook" class="hero-img" />
            <div class="hero-text">
              <h1 class="hero-title">Bienvenido a Everbook</h1>
              <p class="hero-paragraph">
                En Everbook, creemos que el conocimiento es la llave que abre todas las puertas hacia el éxito. Nuestra biblioteca está dedicada a estudiantes y maestros, ofreciendo un espacio acogedor y moderno donde la curiosidad y el aprendizaje se encuentran. Con una amplia colección de libros, recursos digitales y actividades diseñadas para inspirar y apoyar tu crecimiento académico, Everbook es mucho más que una biblioteca: es tu aliado en cada etapa del aprendizaje. Ven y descubre un mundo de posibilidades, porque en Everbook, tu educación comienza aquí.
              </p>
            </div>
          </div>
        </div>
        <button class="btn logout-btn" type="button" onclick="window.location.href='<?= base_url('login/salir') ?>'"> 
    Cerrar Sesión <i class="bi bi-box-arrow-right"></i>
    </button>
      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>