<?php
session_start();
require_once __DIR__ . '/../models/Pedido.php';

if (!isset($_SESSION['usuario'])) {
    die("Debes iniciar sesión para realizar un pedido.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
        die("El carrito está vacío.");
    }

    $usuario_id = $_SESSION['usuario']['id'];
    $total = 0;

    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }

    $pedido_id = Pedido::crearPedido($usuario_id, $total);

    foreach ($_SESSION['carrito'] as $item) {
        Pedido::agregarDetalle($pedido_id, $item['id'], $item['cantidad'], $item['precio'] * $item['cantidad']);
    }

    $_SESSION['carrito'] = [];  // Vaciar carrito tras compra
    header("Location: ../views/pedido/exito.php?pedido_id=" . $pedido_id);
}
?>
