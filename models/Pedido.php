<?php
require_once __DIR__ . '/../config/conexion.php';

class Pedido {
    public static function crearPedido($usuario_id, $total) {
        global $conn;
        $sql = "INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$usuario_id, $total]);
        return $conn->lastInsertId();
    }

    public static function agregarDetalle($pedido_id, $producto_id, $cantidad, $subtotal) {
        global $conn;
        $sql = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, subtotal) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$pedido_id, $producto_id, $cantidad, $subtotal]);
    }
}
?>
