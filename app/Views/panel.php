<!DOCTYPE html>
<html>
<head>
<title>Panel</title>
</head>
<body>
<h1>Bienvenido <?= session('nombre') ?></h1>
<a href="<?= base_url('login/salir') ?>">Cerrar sesión</a>
</body>
</html>