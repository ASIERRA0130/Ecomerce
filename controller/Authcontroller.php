<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'login') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            die("Error: Todos los campos son obligatorios.");
        }

        try {
            $stmt = $conn->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_rol'] = $user['rol']; // ✅ Guardamos el rol del usuario

                header("Location: ../index.php");
                exit();
            } else {
                header("Location: ../views/auth/login.php?error=credenciales");
                exit();
            }
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    } elseif ($action == 'register') {
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $rol = $_POST['rol'] ?? 'cliente'; // ✅ Capturamos el rol desde el formulario

        if (empty($nombre) || empty($email) || empty($password)) {
            die("Error: Todos los campos son obligatorios.");
        }

        // Encriptar la contraseña antes de guardarla
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // ✅ Se usa el rol seleccionado por el usuario
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)");
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':rol', $rol, PDO::PARAM_STR); // <- aquí se pasa el rol

            $stmt->execute();

            header("Location: ../views/auth/login.php?registro=ok");
            exit();
        } catch (PDOException $e) {
            die("Error al registrar: " . $e->getMessage());
        }
    } else {
        die("Error: Acción no permitida.");
    }
} else {
    die("Error: Acción no permitida.");
}
