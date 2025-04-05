<?php
session_start();
require_once __DIR__ . '/config/conexion.php';

// Verifica si hay usuario autenticado y su rol
$usuario_nombre = $_SESSION['user_name'] ?? null;
$usuario_rol = $_SESSION['user_rol'] ?? null;

// Obtener los productos de la base de datos
$stmt = $conn->prepare("SELECT * FROM productos");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar productos por categoría
$productos_por_categoria = [];
foreach ($productos as $producto) {
    $productos_por_categoria[$producto['categoria']][] = $producto;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planeta Digital</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #007bff, #00c6ff);
            margin: 0;
            padding: 0;
            color: white;
        }
        .navbar {
            background: rgba(0, 0, 0, 0.8);
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar .usuario {
            font-size: 16px;
        }
        .navbar a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            margin-left: 15px;
        }
        .container {
            width: 90%;
            margin: auto;
            padding: 20px;
            text-align: center;
        }
        .titulo {
            font-size: 48px;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            margin: 20px 0;
        }
        .reseña {
            font-size: 20px;
            max-width: 900px;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            color: black;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
        .productos {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .producto {
            background: white;
            width: 250px;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            color: black;
        }
        .producto img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }
        .producto h3 {
            font-size: 20px;
            margin: 10px 0;
        }
        .categoria {
            font-size: 16px;
            color: gray;
            margin-bottom: 5px;
        }
        .precio {
            font-size: 22px;
            font-weight: bold;
            color: #007600;
        }
        .btn-carrito, .btn-admin {
            background: #ff5722;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            width: 100%;
            margin-top: 10px;
            font-size: 16px;
            display: block;
            text-align: center;
            text-decoration: none;
        }
        .btn-carrito:hover, .btn-admin:hover {
            background: #e64a19;
        }
        .titulo-categoria {
            font-size: 28px;
            margin-top: 40px;
            text-align: left;
            color: #fff;
            border-bottom: 2px solid rgba(255,255,255,0.4);
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="usuario">
            <?php if ($usuario_nombre): ?>
                👤 <?= htmlspecialchars($usuario_nombre); ?> | 
                <a href="logout.php">Cerrar sesión</a>
                <a href="views/carrito/carrito.php">🛍 Ver Carrito</a>
            <?php else: ?>
                <a href="views/auth/login.php">Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <h1 class="titulo">🌎 PLANETA DIGITAL</h1>

        <div class="reseña">
           <img src="uploads/222.jpg" alt="Tecnología y compras">
            <p>Bienvenido a <strong>Planeta Digital</strong>, tu tienda virtual de confianza donde puedes encontrar los mejores productos de tecnología. Explora nuestra selección de dispositivos electrónicos, accesorios y mucho más. 🚀✨</p>
        </div>

        <?php if ($usuario_rol === 'admin'): ?>
            <div style="margin: 20px 0;">
                <a href="views/productos/create.php" class="btn-admin">➕ Agregar Producto</a>
            </div>
        <?php endif; ?>

        <?php foreach ($productos_por_categoria as $categoria => $productos_categoria): ?>
            <h2 class="titulo-categoria"><?= htmlspecialchars($categoria); ?></h2>
            <div class="productos">
                <?php foreach ($productos_categoria as $producto): ?>
                    <div class="producto">
                        <img src="uploads/<?= htmlspecialchars($producto['imagen']); ?>" alt="<?= htmlspecialchars($producto['nombre']); ?>">
                        <h3><?= htmlspecialchars($producto['nombre']); ?></h3>
                        <p class="categoria">Categoría: <?= htmlspecialchars($producto['categoria']); ?></p>
                        <p><?= nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
                        <p class="precio">$<?= number_format($producto['precio'], 2); ?></p>

                        <?php if ($usuario_nombre): ?>
                            <label>Cantidad:</label>
                            <input type="number" id="cantidad_<?= $producto['id']; ?>" value="1" min="1">
                            <button class="btn-carrito" onclick="agregarAlCarrito(<?= $producto['id']; ?>)">🛒 Agregar al carrito</button>
                        <?php else: ?>
                            <button class="btn-carrito" onclick="alert('Debes iniciar sesión para agregar al carrito')">🔒 Inicia sesión</button>
                        <?php endif; ?>

                        <?php if ($usuario_rol === 'admin'): ?>
                            <a href="views/productos/edit.php?id=<?= $producto['id']; ?>" class="btn-admin" style="background: #2196F3;">✏️ Editar</a>
                            <a href="controller/delete_producto.php?id=<?= $producto['id']; ?>" class="btn-admin" style="background: red;">❌ Eliminar</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function agregarAlCarrito(id) {
            let cantidad = document.getElementById('cantidad_' + id).value;

            fetch('views/carrito/carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id + '&cantidad=' + cantidad
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
            });
        }
    </script>
</body>
</html>
