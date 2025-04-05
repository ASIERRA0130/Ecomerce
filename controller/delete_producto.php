<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
    echo "Acceso denegado";
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener la imagen del producto para eliminarla
    $stmt = $conn->prepare("SELECT imagen FROM productos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        // Eliminar la imagen del servidor
        $rutaImagen = __DIR__ . '/../uploads/' . $producto['imagen'];
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        // Eliminar producto de la base de datos
        $stmt = $conn->prepare("DELETE FROM productos WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header("Location: ../views/productos/index.php");
        exit;
    }
}

echo "Error al eliminar producto.";
?>
