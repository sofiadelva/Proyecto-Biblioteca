<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Login</title>

    <!-- MDB icon -->
    <link rel="icon" 
      href="<?= base_url('fotos/generated-image.png') ?>" 
      type="image/png" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet"/>
</head>
<body>
<section class="vh-100" style="background-color: #197278;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-10">
                <div class="card" style="border-radius: 1rem;">
                    <div class="row g-0">
                        
                        <section class="vh-100" style="background-color: #f5f5f5ff;">
                <div class="container py-5 h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col col-xl-10">
                        <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            
                            <div class="col-md-7 col-lg-5 d-flex align-items-center justify-content-center">
                            <img src="<?= base_url('fotos/libros.png') ?>"
                                alt="login form"
                                class="img-fluid"
                                style="border-radius: 1rem 0 0 1rem; max-width: 90%; max

                        <div class="col-md-7  d-flex align-items-center">
                            <div class="card-body p-4 p-lg-5 text-black w-100">

                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <img src="<?= base_url('fotos/generated-image.png') ?>" 
                                    alt="Logo EverBook" 
                                    style="width: 40px; height: 40px; margin-right: 1rem;" />
                                    <span class="h1 fw-bold mb-0">EverBook</span>
                                </div>


                                <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Iniciar Sesión</h5>

                                <!-- MENSAJE DE ERROR -->
                                <?php if(session()->getFlashdata('error')): ?>
                                    <p class="text-danger"><?= session()->getFlashdata('error') ?></p>
                                <?php endif; ?>

                                <!-- FORMULARIO FUNCIONAL -->
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
                                        <button class="btn btn-dark btn-lg btn-block" type="submit">Ingresar</button>
                                    </div>

                                </form>
                                <!-- FIN FORMULARIO -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MDB -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.js"></script>
</body>
</html>
