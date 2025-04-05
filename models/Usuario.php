<?php
require_once __DIR__ . '/../config/conexion.php';

class Usuario {
    public static function registrar($nombre, $email, $password, $rol = 'cliente') {
        global $conn;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$nombre, $email, $hashedPassword, $rol]);
    }

    public static function verificarCredenciales($email, $password) {
        global $conn;
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }
}
?>
