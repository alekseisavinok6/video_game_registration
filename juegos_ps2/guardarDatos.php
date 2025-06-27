<?php

session_start();
require '../bd.php';

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Usuario no autenticado para guardar datos.";
    header("Location: ../entrada.php");
    exit;
}

$id_usuario_actual = $_SESSION['usuario_id'];

$nombre = $_POST['nombre'] ?? null;
$descripcion = $_POST['descripcion'] ?? null;
$genero = $_POST['genero'] ?? null;

if ($nombre === null || $descripcion === null || $genero === null) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Datos incompletos para guardar el registro.";
    header('Location: index.php');
    exit;
}

$sql = "INSERT INTO videojuegos_ps2 (nombre, descripcion, id_genero, fecha_alta, id_usuario)
        VALUES (?, ?, ?, NOW(), ?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ssii", $nombre, $descripcion, $genero, $id_usuario_actual);
    if ($stmt->execute()) {
        $id = $conn->insert_id;

        $_SESSION['color'] = "primary";
        $_SESSION['msg'] = "Registro guardado";

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $permitidos = array("image/jpg", "image/jpeg");
            if (in_array($_FILES['imagen']['type'], $permitidos)) {
                $dir = "imagenes";
                $imagen_path = $dir . '/' . $id . '.jpg';
            
                if (!file_exists($dir)) {
                    mkdir($dir, 0777);
                }

                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_path)) {
                    $_SESSION['color'] = "danger";
                    $_SESSION['msg'] .= "<br>Fallo al almacenar la imagen";
                }
            } else {
                $_SESSION['color'] = "danger";
                $_SESSION['msg'] .= "<br>Formato de imagen inválido. Solo JPG y JPEG permitidos.";
            }
        }
    } else {
        $_SESSION['color'] = "danger";
        $_SESSION['msg'] = "No se pudo guardar la imagen: " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error al preparar la consulta: " . $conn->error;
}

$conn->close();
header('Location: index.php');
exit;
?>