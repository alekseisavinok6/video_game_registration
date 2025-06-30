<?php

session_start();
require '../database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Unauthenticated user.'
    ]);
    exit();
}

$current_id_user = $_SESSION['id_user'];

if (!isset($_POST['id'])) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Game ID not provided.'
    ]);
    exit();
}
$id = $_POST['id'];

$response = [
    'status' => 'error', 
    'message' => 'Unknown error.'
];

$sql = "SELECT id, title, specification, id_gender
        FROM video_games_xbox
        WHERE id = ? 
        AND id_user = ?
        LIMIT 1";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("ii", $id, $current_id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->num_rows;

    if ($rows > 0) {
        $video_games_xbox = $result->fetch_assoc();
        $response = [
            'status' => 'success', 
            'data' => $video_games_xbox, 
            'id' => $video_games_xbox['id'], 
            'title' => $video_games_xbox['title'], 
            'specification' => $video_games_xbox['specification'], 
            'id_gender' => $video_games_xbox['id_gender']
        ];
    } else {
        $response = [
            'status' => 'error', 
            'message' => 'Video game not found or does not belong to this user.'
        ];
    }
    $stmt->close();
} else {
    $response = [
        'status' => 'error', 
        'message' => 'Error preparing query: ' . $mysqli->error
    ];
}

$mysqli->close();
echo json_encode($response, JSON_UNESCAPED_UNICODE);