<?php
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $_SESSION['carrito'] = array_diff($_SESSION['carrito'], [$id]);
}

header("Location: carrito.php");
exit;
?>
