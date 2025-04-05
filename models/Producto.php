<?php
require_once __DIR__ . '/../config/conexion.php'; // ✅ RUTA CORRECTA

class Producto {
    private static function obtenerConexion() {
        global $conn;
        if (!$conn) {
            die("Error de conexión a la base de datos.");
        }
        return $conn;
    }

    public static function obtenerTodos() {
        $conn = self::obtenerConexion();
        $sql = "SELECT * FROM productos";
        return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($id) {
        $conn = self::obtenerConexion();
        $sql = "SELECT * FROM productos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            return false; // Retorna false si no existe el producto
        }
        return $producto;
    }

    // ❌ Se eliminó el parámetro "cantidad"
    public static function crear($nombre, $descripcion, $precio, $imagen, $categoria) {
        $conn = self::obtenerConexion();
        $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, categoria) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $precio, $imagen, $categoria]);
    }

    // ❌ Se eliminó el parámetro "cantidad"
    public static function actualizar($id, $nombre, $descripcion, $precio, $imagen, $categoria) {
        $conn = self::obtenerConexion();
        $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, imagen = ?, categoria = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $precio, $imagen, $categoria, $id]);
    }

    public static function eliminar($id) {
        $conn = self::obtenerConexion();
        $sql = "DELETE FROM productos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
