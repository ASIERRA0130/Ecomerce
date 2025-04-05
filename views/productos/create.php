<?php
session_start();
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
    die("Acceso denegado.");
}

require_once __DIR__ . '/../../config/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        label {
            font-weight: bold;
        }
        input, textarea, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Agregar Producto</h2>
    <form action="../../controller/ProductoController.php" method="POST" enctype="multipart/form-data">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Descripción:</label>
        <textarea name="descripcion" required></textarea>

        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Categoría:</label>
        <select name="categoria" required>
            <option value="">Seleccione una categoría</option>
            <option value="Portátil">Portátil</option>
            <option value="Computador de mesa">Computador de mesa</option>
            <option value="Teclado">Teclado</option>
            <option value="Mouse">Mouse</option>
            <option value="Parlantes">Parlantes</option>
            <option value="Diademas">Diademas</option>
            <option value="Monitores">Monitores</option>
            <option value="Impresoras">Impresoras</option>
        </select>

        <label>Imagen:</label>
        <input type="file" name="imagen" required>

        <input type="hidden" name="crear" value="1">
        <button type="submit">Guardar Producto</button>
    </form>
</div>

</body>
</html>
