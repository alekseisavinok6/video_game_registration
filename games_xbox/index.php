<?php

session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: entrance.php");
    exit;
}

require '../database.php';

$current_id_user = $_SESSION['id_user'];

$sql_video_games = "SELECT v.id, v.title, v.specification, g.names AS genders 
                   FROM video_games_xbox AS v
                   INNER JOIN genders AS g ON v.id_gender=g.id
                   WHERE v.id_user = ?";

$video_games = null;
if ($stmt = $mysqli->prepare($sql_video_games)) {
    $stmt->bind_param("i", $current_id_user);
    $stmt->execute();
    $video_games = $stmt->get_result();
    $stmt->close();
} else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error preparing video game query: " . $mysqli->error;
}

// Directory for images
$dir = "images/";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xbox video game registration</title>
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <link href="../resources/css/bootstrap.min.css" rel="stylesheet">
    <link href="../resources/css/all.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">
    <div class="container py-3">
        <h2 class="text-center">Xbox video games <i class="fa-brands fa-playstation"></i></h2>
        <hr>
        <div class="alert alert-light text-start">
            Active session for: <strong><?= $_SESSION['user'] ?></strong> with <i>id</i>: <?= $_SESSION['id_user'] ?>
        </div>
        <?php if (isset($_SESSION['msg']) && isset($_SESSION['color'])) { ?>
            <div class="alert alert-<?= $_SESSION['color']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <?php
            unset($_SESSION['color']);
            unset($_SESSION['msg']);
        } ?>

        <div class="row justify-content-end">
            <div class="col text-start">
                <a href="../close.php" class="btn btn-light"><i class="fa-solid fa-xmark"></i> Log out</a>
            </div>
            <div class="col-auto">
                <a href="../games_ps2/index.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-right"></i> Go to PS2</a>
                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#new_window_1"><i class="fa-solid fa-circle-plus"></i> Data upload</a>
            </div>

        </div>

        <table class="table table-sm table-striped table-hover mt-4">
            <thead class="table-success">
                <tr>
                    <th><i>id</i></th>
                    <th>Title</th>
                    <th>Specification</th>
                    <th>Gender</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row_video_games_xbox = $video_games->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row_video_games_xbox['id']; ?></td>
                        <td><?= $row_video_games_xbox['title']; ?></td>
                        <td><?= $row_video_games_xbox['specification']; ?></td>
                        <td><?= $row_video_games_xbox['genders']; ?></td>
                        <td><img src="<?= $dir . $row_video_games_xbox['id'] . '.jpg?n=' . time(); ?>" width="100"></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit_window_1" data-bs-id="<?= $row_video_games_xbox['id']; ?>"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete_window_1" data-bs-id="<?= $row_video_games_xbox['id']; ?>"><i class="fa-solid fa-trash"></i></i> Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <p class="text-center"><i>Video game registration - 2025</i></a> <i class="fa-solid fa-gamepad"></i></p>
        </div>
    </footer>

    <?php
    $sql_gender = "SELECT id, names FROM genders";
    $genders = $mysqli->query($sql_gender);

    $mysqli->close();
    ?>

    <?php include 'new_window.php'; ?>

    <?php $genders->data_seek(0); ?>

    <?php include 'edit_window.php'; ?>
    <?php include 'delete_window.php'; ?>

    <script>
        let newWindow = document.getElementById('new_window_1')
        let editWindow = document.getElementById('edit_window_1')
        let deleteWindow = document.getElementById('delete_window_1')
        
        newWindow.addEventListener('shown.bs.modal', event => {
            newWindow.querySelector('.modal-body #title').focus()
        })
        
        newWindow.addEventListener('hide.bs.modal', event => {
            newWindow.querySelector('.modal-body #title').value = ""
            newWindow.querySelector('.modal-body #specification').value = ""
            newWindow.querySelector('.modal-body #genders').value = ""
            newWindow.querySelector('.modal-body #image').value = ""
        })
        
        editWindow.addEventListener('hide.bs.modal', event => {
            editWindow.querySelector('.modal-body #id').value = ""
            editWindow.querySelector('.modal-body #title').value = ""
            editWindow.querySelector('.modal-body #specification').value = ""
            editWindow.querySelector('.modal-body #genders').value = ""
            editWindow.querySelector('.modal-body #img_image').src = ""
            editWindow.querySelector('.modal-body #image').value = ""
        })
        
        editWindow.addEventListener('shown.bs.modal', event => {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
        
            let inputId = editWindow.querySelector('.modal-body #id')
            let inputTitle = editWindow.querySelector('.modal-body #title')
            let inputSpecification = editWindow.querySelector('.modal-body #specification')
            let inputGenders = editWindow.querySelector('.modal-body #genders')
            let image = editWindow.querySelector('.modal-body #img_image')
        
            let url = "get_video_game.php"
            let formData = new FormData()
            formData.append('id', id)
        
            fetch(url, {
                method: "POST",
                body: formData
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
                .then(data => {
                if (data.status === 'success' && data.data) {
                    inputId.value = data.data.id;
                    inputTitle.value = data.data.title;
                    inputSpecification.value = data.data.specification;
                    inputGenders.value = data.data.id_genders;
                    // Load updated image without cache
                    image.src = '<?= $dir ?>' + data.data.id + '.jpg?' + new Date().getTime();
                } else {
                    alert('Error getting data from the video game: ' + (data.message || 'Data not available.'));
                    editWindow.querySelector('.btn-close').click(); 
                }
            }).catch(err => {
                console.error('Fetch error:', err);
                alert('Error connecting to the server to retrieve game data. Check your console for details.');
                editWindow.querySelector('.btn-close').click();
            });
        });
        
        deleteWindow.addEventListener('shown.bs.modal', event => {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            deleteWindow.querySelector('.modal-footer #id').value = id
        })
    </script>


    <script src="../resources/js/bootstrap.bundle.min.js"></script>
</body>
</html>