<?php
require_once '../config/conexion.php';
require_once '../models/Producto.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];

    // Manejo de imagen
    if ($_FILES['imagen']['name']) {
        $directorio = '../uploads/';
        $nombreImagen = basename($_FILES['imagen']['name']);
        $rutaImagen = $directorio . $nombreImagen;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            $imagen = $nombreImagen;
        } else {
            die("Error al subir la imagen.");
        }
    } else {
        $imagen = $_POST['imagen_actual'];
    }

    // Actualizar el producto en la base de datos
    $resultado = Producto::actualizarProducto($id, $nombre, $descripcion, $precio, $imagen, $categoria);

    if ($resultado) {
        header("Location: ../index.php?mensaje=Producto actualizado correctamente");
        exit();
    } else {
        die("Error al actualizar el producto.");
    }
}
