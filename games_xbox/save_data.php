<?php
session_start();
require '../database.php';

if (!isset($_SESSION['id_user'])) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Unauthenticated user to save data";
    header("Location: ../entrance.php");
    exit;
}

//$current_id_user = $_SESSION['id_user'];

$title = $_POST['title'] ?? null;
$specification = $_POST['specification'] ?? null;
//$genders = $_POST['genders'] ?? null;
$genders = isset($_POST['genders']) ? (int)$_POST['genders'] : null;
$current_id_user = (int)$_SESSION['id_user'];

if ($title === null || $specification === null || $genders === null) {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Incomplete data to save the record";
    header('Location: index.php');
    exit;
}
//var_dump($title, $specification, $genders, $current_id_user);

$sql = "INSERT INTO video_games_xbox (title, specification, id_gender, id_user, creation_date)
        VALUES (?, ?, ?, ?, NOW())";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("ssii", $title, $specification, $genders, $current_id_user);
    if ($stmt->execute()) {
        $id = $mysqli->insert_id;

        $_SESSION['color'] = "success";
        $_SESSION['msg'] = "Record saved";

        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $allowed = array("image/jpg", "image/jpeg");
            if (in_array($_FILES['image']['type'], $allowed)) {
                $dir = "images";
                $image_path = $dir . '/' . $id . '.jpg';
            
                if (!file_exists($dir)) {
                    mkdir($dir, 0777);
                }

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    $_SESSION['color'] = "danger";
                    $_SESSION['msg'] .= "<br>Failed to store image";
                }
            } else {
                $_SESSION['color'] = "danger";
                $_SESSION['msg'] .= "<br>Invalid image format. Only JPG and JPEG are allowed.";
            }
        }
    } else {
        $_SESSION['color'] = "danger";
        $_SESSION['msg'] = "The image could not be saved: " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error preparing query: " . $mysqli->error;
}

$mysqli->close();
header('Location: index.php');
exit;
?>