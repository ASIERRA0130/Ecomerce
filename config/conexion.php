<?php
// Iniciar sesión solo si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Mostrar errores de PHP (solo en desarrollo, desactiva en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de la base de datos
$host = "localhost";
$dbname = "comercio2025"; // Asegúrate de que este nombre es correcto
$username = "root"; // Usuario por defecto en XAMPP
$password = ""; // XAMPP no tiene contraseña por defecto

try {
    // Crear la conexión usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Mostrar un mensaje de error en caso de fallo
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
