<?php
session_start();
require 'db.php';

if (isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $user_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);

    $mysqli->query("INSERT INTO users (first_name, last_name, email, user_password)
                      VALUES ('$first_name', '$last_name', '$email', '$user_password')");
    $message = "Successfully registered. Sign in.";
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $user_password = $_POST['user_password'];

    $result = $mysqli->query("SELECT * FROM users WHERE email = '$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($user_password, $user['user_password'])) {
            $_SESSION['user'] = $user['first_name'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: games_ps2/index.php");
            exit;
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Access to the App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/9aa3d67044.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Welcome to the WebApp for managing video game collections!</h2>
    <?php if (isset($message)): ?>
        <div class="alert alert-info text-center"><?= $message ?></div>
    <?php endif; ?>
    <div class="row justify-content-start">
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <i class="fa-solid fa-user-plus"></i> Record
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>First name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Last name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="user_password" class="form-control" required>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary w-100">
                            Register
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <i class="fa-solid fa-right-to-bracket"></i> Login
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="user_password" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-success w-100">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-6 d-flex justify-content-start">
            <img src="img_entrance.jpg" alt="Imagen Login" style="width: 500px; height: 265px;">
        </div>
        <p class="text-center mb-4"><i>Miami, 2025</i></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
