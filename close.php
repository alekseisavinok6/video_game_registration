<?php
// Log out the user

session_start();
session_unset();

if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/video_game_registration/');
}

session_destroy();
header("Location: entrance.php");
exit;