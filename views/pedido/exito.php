<?php
session_start();
if (!isset($_GET['pedido_id'])) {
    die("No se encontró el pedido.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Compra Exitosa</title>
</head>
<body>
    <h2>¡Gracias por tu compra! 🎉</h2>
    <p>Tu pedido ha sido registrado con el número: <strong>#<?= $_GET['pedido_id'] ?></strong></p>
    <a href="../productos/index.php">Volver a la tienda</a>
</body>
</html>
