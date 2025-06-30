<?php

session_start();
require '../database.php';

if (!isset($_SESSION['id_user'])) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Unauthenticated user to delete data.";
    header("Location: ../entrance.php");
    exit;
}

$current_id_user = $_SESSION['id_user'];

$id = $_POST['id'] ?? null;

if ($id === null) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Game ID not provided.";
    header('Location: index.php');
    exit;
}

$sql = "DELETE FROM video_games_xbox
        WHERE id = ?
        AND id_user = ?";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("ii", $id, $current_id_user);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $dir = "images";
            $image = $dir . '/' . $id . '.jpg';

            if (file_exists($image)) {
                if (unlink($image)) {
                    $_SESSION['color'] = "success";
                    $_SESSION['msg'] = "Record deleted";
                } else {
                    $_SESSION['color'] = "warning";
                    $_SESSION['msg'] = "Record deleted, but the image to delete was not found";
                }
            } else {
                $_SESSION['color'] = "success";
                $_SESSION['msg'] = "Record deleted";
            }    
        } else {
            $_SESSION['color'] = "danger";
            $_SESSION['msg'] = "Failed to delete record";
        }
    } else {
        $_SESSION['color'] = "danger";
        $_SESSION['msg'] = "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error preparing delete query: " . $mysqli->error;
}

$mysqli->close();
header('Location: index.php');
exit;

?>