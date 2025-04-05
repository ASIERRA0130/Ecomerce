<?php
session_start();
require_once '../../models/Producto.php';

// Verificar si el usuario está autenticado y si es administrador
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['rol']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../../index.php"); // Redirigir al inicio si no es admin
    exit();
}

$productos = Producto::obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestión de Productos</title>
</head>
<body>
    <h2>Productos</h2>
    <a href="create.php">Añadir Producto</a>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Imagen</th>
            <th>Categoría</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                <td>$<?= number_format($producto['precio'], 2) ?></td>
                <td><img src="<?= htmlspecialchars($producto['imagen']) ?>" width="50"></td>
                <td><?= htmlspecialchars($producto['categoria']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $producto['id'] ?>">Editar</a> |
                    <a href="../../controller/ProductoController.php?eliminar=<?= $producto['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <td>
        <form action="../../controller/CarritoController.php" method="POST">
            <input type="hidden" name="id" value="<?= $producto['id'] ?>">
            <input type="number" name="cantidad" value="1" min="1">
            <button type="submit" name="agregar">Agregar al Carrito</button>
        </form>
    </td>

    <a href="../carrito/index.php">🛒 Ver Carrito</a>
</body>
</html>
