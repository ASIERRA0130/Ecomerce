<?php
session_start();
require_once '../models/Producto.php';
require_once '../config/conexion.php';

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    header("Location: ../views/productos/index.php?error=No tienes permisos");
    exit();
}

// Verificar si la solicitud es POST y se está editando un producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $categoria = trim($_POST['categoria']);

    // Validar que el ID del producto es válido
    if ($id <= 0) {
        header("Location: ../views/productos/index.php?error=ID de producto no válido");
        exit();
    }

    // Obtener el producto actual
    $productoActual = Producto::obtenerPorId($id);
    if (!$productoActual) {
        header("Location: ../views/productos/index.php?error=Producto no encontrado");
        exit();
    }

    // Manejo de la imagen
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = time() . "_" . basename($_FILES['imagen']['name']); // Evitar nombres duplicados
        $rutaDestino = "../uploads/" . $imagen;
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            header("Location: ../views/productos/index.php?error=Error al subir la imagen");
            exit();
        }
    } else {
        $imagen = $productoActual['imagen']; // Mantener la imagen actual
    }

    // Actualizar producto en la base de datos
    $actualizado = Producto::actualizar($id, $nombre, $descripcion, $precio, $imagen, $categoria);

    if ($actualizado) {
        header("Location: ../views/productos/index.php?success=Producto actualizado correctamente");
        exit();
    } else {
        header("Location: ../views/productos/index.php?error=Error al actualizar el producto");
        exit();
    }
}

// Verificar si la solicitud es POST y se está creando un nuevo producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $categoria = trim($_POST['categoria']);

    // Manejo de la imagen
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = time() . "_" . basename($_FILES['imagen']['name']); // Evitar nombres duplicados
        $rutaDestino = "../uploads/" . $imagen;
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            header("Location: ../views/productos/index.php?error=Error al subir la imagen");
            exit();
        }
    } else {
        $imagen = ""; // Dejar la imagen vacía si no se sube ninguna
    }

    // Crear el nuevo producto
    $creado = Producto::crear($nombre, $descripcion, $precio, $imagen, $categoria);

    if ($creado) {
        header("Location: ../views/productos/index.php?success=Producto creado correctamente");
        exit();
    } else {
        header("Location: ../views/productos/index.php?error=Error al crear el producto");
        exit();
    }
}

// Si el usuario intenta acceder sin enviar datos válidos, redirigirlo
header("Location: ../views/productos/index.php?error=Acceso denegado");
exit();
?>
