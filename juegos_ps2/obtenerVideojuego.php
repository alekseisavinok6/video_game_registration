<?php

session_start();
require '../bd.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Usuario no autenticado.'
    ]);
    exit();
}
$id_usuario_actual = $_SESSION['usuario_id'];

if (!isset($_POST['id'])) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'ID del videojuego no proporcionado.'
    ]);
    exit();
}
$id = $_POST['id'];

$response = [
    'status' => 'error', 
    'message' => 'Error desconocido.'
];

$sql = "SELECT id, nombre, descripcion, id_genero 
        FROM videojuegos_ps2 
        WHERE id = ? 
        AND id_usuario = ?
        LIMIT 1";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $id, $id_usuario_actual);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->num_rows;

    if ($rows > 0) {
        $videojuegos_ps2 = $result->fetch_assoc();
        $response = [
            'status' => 'success', 
            'data' => $videojuegos_ps2, 
            'id' => $videojuegos_ps2['id'], 
            'nombre' => $videojuegos_ps2['nombre'], 
            'descripcion' => $videojuegos_ps2['descripcion'], 
            'id_genero' => $videojuegos_ps2['id_genero']
        ];
    } else {
        $response = [
            'status' => 'error', 
            'message' => 'Videojuego no encontrado o no pertenece a este usuario.'
        ];
    }
    $stmt->close();
} else {
    $response = [
        'status' => 'error', 
        'message' => 'Error al preparar la consulta: ' . $conn->error
    ];
}

$conn->close();
echo json_encode($response, JSON_UNESCAPED_UNICODE);