<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "videojuegos");

if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $pass = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $conexion->query("INSERT INTO usuarios (nombre, apellido, correo, contrasena)
                      VALUES ('$nombre', '$apellido', '$correo', '$pass')");
    $mensaje = "Registrado correctamente. Inicia sesión.";
}

if (isset($_POST['login'])) {
    $correo = $_POST['correo'];
    $pass = $_POST['contrasena'];

    $result = $conexion->query("SELECT * FROM usuarios WHERE correo = '$correo'");
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if (password_verify($pass, $usuario['contrasena'])) {
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['usuario_id'] = $usuario['id'];
            header("Location: juegos_ps2/index.php");
            exit;
        } else {
            $mensaje = "Contraseña incorrecta.";
        }
    } else {
        $mensaje = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso a la App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/9aa3d67044.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">¡Bienvenido a la WebApp para la gestión de colecciones de videojuegos!</h2>
    <?php if (isset($mensaje)): ?>
        <div class="alert alert-info text-center"><?= $mensaje ?></div>
    <?php endif; ?>
    <div class="row justify-content-start">
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <i class="fa-solid fa-user-plus"></i> Registro
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Apellido</label>
                            <input type="text" name="apellido" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Correo</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Contraseña</label>
                            <input type="password" name="contrasena" class="form-control" required>
                        </div>
                        <button type="submit" name="registrar" class="btn btn-primary w-100">
                            Registrarse
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Correo</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Contraseña</label>
                            <input type="password" name="contrasena" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-success w-100">
                            Iniciar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-6 d-flex justify-content-start">
            <img src="img_entrada.jpg" alt="Imagen Login" style="width: 500px; height: 265px;">
        </div>
        <p class="text-center mb-4"><i>ILERNA, 2025</i></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
