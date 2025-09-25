<!-- Plantilla oficial de Login EverBook -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Adaptabilidad en móviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login EverBook</title>

    <!-- Icono de la pestaña -->
    <link rel="icon" href="<?= base_url('fotos/generated-image.png') ?>" type="image/png" />

    <!-- Librerías externas -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet"/>

    <style>
        /* Estilos principales de la vista login */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #197278 !important;
        }
        .center-card {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #197278;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 2px 16px rgba(0,0,0,.08);
            /* Se puede eliminar el fondo blanco transparente adicional si no es necesario */
        }
    </style>
</head>
<body>
    <!-- Contenedor centrado -->
    <div class="center-card">
        <!-- Card principal -->
        <div class="card" style="width: 900px; max-width: 98vw;">
            <div class="row g-0">
                
                <!-- Columna izquierda: imagen -->
                <div class="col-md-5 d-flex align-items-center justify-content-center" style="background: none;">
                    <img src="<?= base_url('fotos/libros.png') ?>"
                        alt="login form"
                        class="img-fluid"
                        style="border-radius: 1rem 0 0 1rem;max-width: 95%;" />
                </div>

                <!-- Columna derecha: formulario -->
                <div class="col-md-7 d-flex align-items-center">
                    <div class="card-body p-4 p-lg-5 text-black w-100">
                        
                        <!-- Logo + título -->
                        <div class="d-flex align-items-center mb-3 pb-1">
                            <img src="<?= base_url('fotos/generated-image.png') ?>" 
                            alt="Logo EverBook" 
                            style="width: 40px; height: 40px; margin-right: 1rem;" />
                            <span class="h1 fw-bold mb-0">EverBook</span>
                        </div>

                        <!-- Subtítulo -->
                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Iniciar Sesión</h5>

                        <!-- Mensaje de error en caso de fallo -->
                        <?php if(session()->getFlashdata('error')): ?>
                            <p class="text-danger"><?= session()->getFlashdata('error') ?></p>
                        <?php endif; ?>

                        <!-- Formulario de login -->
                        <form method="post" action="<?= base_url('login/autenticar') ?>">
                            
                            <!-- Usuario -->
                            <div class="form-outline mb-4">
                                <input type="text" name="usuario" id="form2Example17" placeholder="Usuario" class="form-control form-control-lg" required />
                                <label class="form-label" for="form2Example17">Usuario</label>
                            </div>
                            
                            <!-- Contraseña -->
                            <div class="form-outline mb-4">
                                <input type="password" name="password" id="form2Example27" placeholder="Contraseña" class="form-control form-control-lg" required />
                                <label class="form-label" for="form2Example27">Contraseña</label>
                            </div>
                            
                            <!-- Botón de acceso -->
                            <div class="pt-1 mb-4">
                                <button class="btn btn-dark btn-lg btn-block" type="submit">Ingresar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- row g-0 -->
        </div> <!-- card -->
    </div> <!-- center-card -->

    <!-- Script MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.js"></script>
</body>
</html>
