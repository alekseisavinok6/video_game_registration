<?php
session_start();
session_destroy();
header("Location: entrada.php");
exit;