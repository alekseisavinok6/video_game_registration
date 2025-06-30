<?php

session_start();
require '../database.php';

if (!isset($_SESSION['id_user'])) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Unauthenticated user to update data";
    header('Location: entrance.php');
    exit;
}

$current_id_user = $_SESSION['id_user'];

$id = $_POST['id'] ?? null;
$title = $_POST['title'] ?? null;
$specification = $_POST['specification'] ?? null;
$genders = $_POST['genders'] ?? null;

if ($id === null || $title === null || $specification === null || $genders === null) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Incomplete data for update";
    header('Location: entrance.php');
    exit;
}

$sql = "UPDATE video_games_xbox
        SET title = ?, specification = ?, id_gender = ? 
        WHERE id = ?
        AND id_user = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssiii", $title, $specification, $genders, $id, $current_id_user);
        if ($stmt->execute()) {
        $_SESSION['color'] = "success";
        $_SESSION['msg'] = "Updated record";

        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $allowed = array("image/jpg", "image/jpeg");
            if (in_array($_FILES['image']['type'], $allowed)) {
                $dir = "images";
                $image_path = $dir . '/' . $id . '.jpg';

                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    $_SESSION['color'] = "danger";
                    $_SESSION['msg'] .= "<br>The image could not be saved";
                }
            } else {
                $_SESSION['color'] = "danger";
                $_SESSION['msg'] .= "<br>Invalid image format";
            }
        }
    } else {
        $_SESSION['color'] = "danger";
        $_SESSION['msg'] = "Error executing update: " . $stmt->error;
    }
} else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error preparing update query: " . $mysqli->error;
}
$mysqli->close();
header('Location: index.php');
exit;
?>