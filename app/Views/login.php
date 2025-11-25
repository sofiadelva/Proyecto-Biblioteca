<!-- Plantilla oficial de Login EverBook -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login ReadZone</title>

    <link rel="icon" href="<?= base_url('fotos/scj.png') ?>" type="image/png" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet"/>

    <style>
    /* 1. Tipografía "Beyond The Mountains" */
    @font-face {
        font-family: 'beyond_the_mountains';
        /* NOTA: Asegúrate de que esta ruta sea correcta en tu entorno CodeIgniter */
        src: url('<?= base_url('fonts/beyond_the_mountains.otf') ?>') format('opentype'); 
        font-weight: normal;
        font-style: normal;
        font-display: swap; 
    }

    /* Ajuste de Body para centrar la tarjeta verticalmente sin un div contenedor */
    body, html {
        height: 100%;
        margin: 0;
        padding: 0;
        /* Fondo de la imagen (manteniendo el original) */
        background: url('<?= base_url('fotos/Fondo login.png') ?>') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        background-color: #197278 !important; 
        
        /* Centrado de la tarjeta sin DIV contenedor */
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    
    .card {
        border-radius: 1rem;
        box-shadow: 0 2px 16px rgba(0,0,0,.08);
        width: 900px;
        max-width: 98vw;
        overflow: hidden; /* Asegura que la tarjeta contenga todo */
        display: flex; /* Para controlar la estructura interna */
        flex-direction: column;
    }

    /* Estilo para el logo/título usando la nueva fuente */
    .readzone-title {
        font-family: 'beyond_the_mountains', cursive;
        font-size: 4rem; 
        line-height: 1;
        margin-top: 5px;
        margin-bottom: 0 !important;
        font-weight: normal !important;
        color: #0C1E44; 
    }

    /* NUEVO Estilo para la línea de créditos integrada en la tarjeta */
    .footer-credits-integrated {
        padding: 10px 20px;
        text-align: center;
        font-size: 0.8rem;
        color: #6c757d; /* Gris sutil */
        border-top: 1px solid #e9ecef; /* Línea separadora */
        width: 100%;
        box-sizing: border-box;
    }
    
    /* Contenido de la tarjeta (imagen + formulario) para excluir los créditos */
    .card-content {
        display: flex;
        flex-grow: 1;
    }
    
    /* Aseguramos que el contenido dentro de .row g-0 crezca */
    .row.g-0 {
        width: 100%;
        flex-grow: 1;
    }

    </style>

</head>
<body>
    
    <section class="card">
        
        <div class="card-content row g-0">
            
            <div class="col-md-5 d-flex align-items-center justify-content-center" style="background: none;">
                <img src="<?= base_url('fotos/Ardilla.png') ?>"
                    alt="login form"
                    class="img-fluid"
                    style="border-radius: 1rem 0 0 1rem;max-width: 95%;" />
            </div>

            <div class="col-md-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black w-100">
                    
                    <div class="d-flex align-items-center mb-3 pb-1">
                        <span class="readzone-title">ReadZone</span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Iniciar Sesión</h5>

                    <?php if(session()->getFlashdata('error')): ?>
                        <p class="text-danger"><?= session()->getFlashdata('error') ?></p>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('login/autenticar') ?>">
                        
                        <div class="form-outline mb-4">
                            <input type="text" name="usuario" id="form2Example17" placeholder="Usuario" class="form-control form-control-lg" required />
                            <label class="form-label" for="form2Example17">Usuario</label>
                        </div>
                        
                        <div class="form-outline mb-4">
                            <input type="password" name="password" id="form2Example27" placeholder="Contraseña" class="form-control form-control-lg" required />
                            <label class="form-label" for="form2Example27">Contraseña</label>
                        </div>
                        
                        <div class="pt-1 mb-4">
                            <button class="btn btn-dark btn-lg btn-block" type="submit" style="background-color: #0C1E44;">Ingresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> <footer class="footer-credits-integrated">
            Página realizada por: Sofía del Valle Ajosal y Emily Abril Santizo Urízar - Promo 2025
        </footer>

    </section> <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.js"></script>
</body>
</html>