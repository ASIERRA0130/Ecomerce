<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    die("Debes iniciar sesión para realizar una compra.");
}

$total = 0;
if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
} else {
    die("El carrito está vacío.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Finalizar Compra</title>
</head>
<body>
    <h2>Confirmación de Pedido</h2>
    <p>Total a pagar: <strong>$<?= number_format($total, 2) ?></strong></p>

    <form action="../../controller/PedidoController.php" method="POST">
        <button type="submit">Confirmar Compra</button>
    </form>

    <a href="../carrito/index.php">Volver al Carrito</a>
</body>
</html>
