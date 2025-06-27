<?php

session_start();
require '../bd.php';

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Usuario no autenticado para actualizar.";
    header('Location: entrada.php');
    exit;
}

$id_usuario_actual = $_SESSION['usuario_id'];

$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? null;
$descripcion = $_POST['descripcion'] ?? null;
$genero = $_POST['genero'] ?? null;

if ($id === null || $nombre === null || $descripcion === null || $genero === null) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Datos incompletos para la actualización.";
    header('Location: entrada.php');
    exit;
}

$sql = "UPDATE videojuegos_ps2 
        SET nombre = ?, descripcion = ?, id_genero = ? 
        WHERE id = ?
        AND id_usuario = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssiii", $nombre, $descripcion, $genero, $id, $id_usuario_actual);
        if ($stmt->execute()) {
        $_SESSION['color'] = "primary";
        $_SESSION['msg'] = "Registro actualizado.";

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $permitidos = array("image/jpg", "image/jpeg");
            if (in_array($_FILES['imagen']['type'], $permitidos)) {
                $dir = "imagenes";
                $imagen_path = $dir . '/' . $id . '.jpg';

                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_path)) {
                    $_SESSION['color'] = "danger";
                    $_SESSION['msg'] .= "<br>No se pudo guardar la imagen";
                }
            } else {
                $_SESSION['color'] = "danger";
                $_SESSION['msg'] .= "<br>Formato de imagen inválido";
            }
        }
    } else {
        $_SESSION['color'] = "danger";
        $_SESSION['msg'] = "Error al ejecutar la actualización: " . $stmt->error;
    }
} else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error al preparar la consulta de actualización: " . $conn->error;
}
$conn->close();
header('Location: index.php');
exit;
?>