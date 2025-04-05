<?php
session_start();
require_once __DIR__ . '/../models/Producto.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto al carrito
if (isset($_POST['agregar'])) {
    $id = $_POST['id'];
    $producto = Producto::obtenerPorId($id);

    if ($producto) {
        $_SESSION['carrito'][$id] = [
            'id' => $producto['id'],
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'cantidad' => $_POST['cantidad']
        ];
    }
    header("Location: ../views/carrito/index.php");
}

// Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    unset($_SESSION['carrito'][$id]);
    header("Location: ../views/carrito/index.php");
}

// Vaciar carrito
if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
    header("Location: ../views/carrito/index.php");
}
?>
