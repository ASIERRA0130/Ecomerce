<?php
session_start();
require_once '../../models/Producto.php';

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_rol'])) {
    die("Error: No hay usuario en sesión.");
}

if ($_SESSION['user_rol'] !== 'admin') {
    die("Error: No tienes permisos de administrador.");
}

// Validar que el ID de producto esté presente y sea un número
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de producto no válido.");
}

$id = intval($_GET['id']); // Convertir a número entero para mayor seguridad

// Obtener el producto
$producto = Producto::obtenerPorId($id);

// Verificar si el producto existe
if (!$producto) {
    die("Error: Producto no encontrado en la base de datos.");
}

// Lista de categorías disponibles
$categorias = [
    "Portátil", "Computador de mesa", "Teclado", "Mouse", 
    "Parlantes", "Diademas", "Monitores", "Impresoras"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - Planeta Digital</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            text-align: left;
        }
        input, textarea, select, button {
            margin-top: 5px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border: none;
            margin-top: 15px;
        }
        button:hover {
            background-color: #218838;
        }
        .image-preview {
            margin-top: 10px;
        }
        .image-preview img {
            width: 100px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .back-link {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Producto</h2>
        <form action="../../controller/ProductoController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>

            <label>Descripción:</label>
            <textarea name="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>

            <label>Precio:</label>
            <input type="number" name="precio" value="<?= number_format($producto['precio'], 2, '.', '') ?>" required step="0.01">

            <label>Imagen actual:</label>
            <div class="image-preview">
                <?php if (!empty($producto['imagen'])): ?>
                    <img src="../../uploads/<?= htmlspecialchars($producto['imagen']) ?>" alt="Imagen del producto">
                <?php else: ?>
                    <p>No hay imagen disponible.</p>
                <?php endif; ?>
            </div>

            <label>Nueva imagen:</label>
            <input type="file" name="imagen">

            <label>Categoría:</label>
            <select name="categoria" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= htmlspecialchars($categoria) ?>" 
                        <?= ($producto['categoria'] == $categoria) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($categoria) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="editar">Actualizar</button>
        </form>
        <a class="back-link" href="index.php">Volver</a>
    </div>
</body>
</html>
