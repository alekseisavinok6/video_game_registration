<?php

session_start();
require '../bd.php';

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Usuario no autenticado para eliminar datos.";
    header("Location: ../entrada.php");
    exit;
}

$id_usuario_actual = $_SESSION['usuario_id'];

$id = $_POST['id'] ?? null;

if ($id === null) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "ID del videojuego no proporcionado.";
    header('Location: index2.php');
    exit;
}

$sql = "DELETE FROM videojuegos_xbox
        WHERE id = ?
        AND id_usuario = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $id, $id_usuario_actual);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $dir = "imagenes";
            $imagen = $dir . '/' . $id . '.jpg';

            if (file_exists($imagen)) {
                if (unlink($imagen)) {
                    $_SESSION['color'] = "success";
                    $_SESSION['msg'] = "Registro eliminado";
                } else {
                    $_SESSION['color'] = "warning";
                    $_SESSION['msg'] = "Registro eliminado, pero no se encontró la imagen para borrar";
                }
            } else {
                $_SESSION['color'] = "success";
                $_SESSION['msg'] = "Registro eliminado";
            }    
        } else {
            $_SESSION['color'] = "danger";
            $_SESSION['msg'] = "Fallo al eliminar registro";
        }
    } else {
        $_SESSION['color'] = "danger";
        $_SESSION['msg'] = "Error al eliminar el registro: " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error al preparar la consulta de eliminación: " . $conn->error;
}

$conn->close();
header('Location: index2.php');
exit;

?>