<?php
session_start();
require_once __DIR__ . '/../../config/conexion.php';

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['cantidad'])) {
    $id_producto = intval($_POST['id']);
    $cantidad = intval($_POST['cantidad']);

    if ($id_producto > 0 && $cantidad > 0) {
        $_SESSION['carrito'][$id_producto] = $cantidad;
    }
    exit('agregado'); // Respuesta para AJAX
}

// Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    unset($_SESSION['carrito'][$id_eliminar]);
    header('Location: carrito.php');
    exit();
}

// Obtener productos del carrito
$productos_en_carrito = [];
$total = 0;

if (!empty($_SESSION['carrito'])) {
    $ids = implode(',', array_keys($_SESSION['carrito']));
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id IN ($ids)");
    $stmt->execute();
    $productos_en_carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0; }
        .container { width: 90%; max-width: 900px; margin: 30px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #333; }
        .carrito-item { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #ddd; padding: 15px 0; }
        .carrito-item img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; }
        .info { flex-grow: 1; padding-left: 15px; }
        .info h3 { margin: 0; font-size: 18px; color: #333; }
        .precio { font-size: 16px; font-weight: bold; color: #007600; }
        .cantidad { font-size: 14px; color: #555; }
        .eliminar { background: red; color: white; padding: 8px 12px; border: none; cursor: pointer; border-radius: 5px; }
        .eliminar:hover { background: darkred; }
        .total { text-align: right; font-size: 22px; font-weight: bold; margin-top: 20px; }
        .botones { display: flex; justify-content: space-between; margin-top: 20px; }
        .seguir-comprando, .comprar, .volver-tienda { display: inline-block; text-align: center; padding: 12px; border-radius: 5px; text-decoration: none; font-size: 16px; font-weight: bold; width: 48%; }
        .seguir-comprando { background: #007bff; color: white; }
        .seguir-comprando:hover { background: #0056b3; }
        .comprar { background: #28a745; color: white; }
        .comprar:hover { background: #1e7e34; }
        .volver-tienda { background: #6c757d; color: white; width: 100%; text-align: center; margin-top: 10px; }
        .volver-tienda:hover { background: #5a6268; }
    </style>
</head>
<body>

<div class="container">
    <h2>🛒 Carrito de Compras</h2>

    <?php if (empty($productos_en_carrito)): ?>
        <p style="text-align: center;">Tu carrito está vacío.</p>
        <a href="../../index.php" class="volver-tienda">📝 Volver a la tienda</a>
    <?php else: ?>
        <?php foreach ($productos_en_carrito as $producto): ?>
            <div class="carrito-item">
                <img src="../../uploads/<?= htmlspecialchars($producto['imagen']); ?>" alt="<?= htmlspecialchars($producto['nombre']); ?>">
                <div class="info">
                    <h3><?= htmlspecialchars($producto['nombre']); ?></h3>
                    <p class="precio">$<?= number_format($producto['precio'], 2); ?></p>
                    <label>Cantidad:</label>
                    <input type="number" id="cantidad_<?= $producto['id']; ?>" value="<?= $_SESSION['carrito'][$producto['id']]; ?>" min="1">
                    <button onclick="actualizarCarrito(<?= $producto['id']; ?>)">🔄</button>
                </div>
                <button class="eliminar" onclick="eliminarProducto(<?= $producto['id']; ?>)">❌</button>
            </div>
            <?php $total += $producto['precio'] * $_SESSION['carrito'][$producto['id']]; ?>
        <?php endforeach; ?>

        <div class="total">Total: $<?= number_format($total, 2); ?></div>

        <div class="botones">
            <a href="../../index.php" class="seguir-comprando">🛒 Seguir comprando</a>
            <a href="#" class="comprar">💳 Finalizar compra</a>
        </div>
    <?php endif; ?>
</div>

<script>
    function actualizarCarrito(id) {
        let cantidad = document.getElementById('cantidad_' + id).value;
        
        if (cantidad > 0) {
            fetch('carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id + '&cantidad=' + cantidad
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'agregado') {
                    location.reload();
                }
            });
        } else {
            alert('La cantidad debe ser mayor a 0.');
        }
    }

    function eliminarProducto(id) {
        if (confirm('¿Estás seguro de eliminar este producto?')) {
            window.location.href = "carrito.php?eliminar=" + id;
        }
    }
</script>

</body>
</html>
