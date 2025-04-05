<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Planeta Digital</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #007bff, #00c6ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 350px;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-login {
            background: #007bff;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-login:hover {
            background: #0056b3;
        }
        .error, .success {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .error {
            background: #ffdddd;
            color: #d8000c;
        }
        .success {
            background: #ddffdd;
            color: #4F8A10;
        }
        .register-link {
            display: block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🔐 Iniciar Sesión</h2>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'credenciales'): ?>
            <p class="error">Correo o contraseña incorrectos.</p>
        <?php endif; ?>

        <?php if (isset($_GET['registro']) && $_GET['registro'] == 'ok'): ?>
            <p class="success">Registro exitoso. Inicia sesión.</p>
        <?php endif; ?>

        <form action="/ecomerce/controller/AuthController.php?action=login" method="POST">
            <div class="input-group">
                <label for="email">Correo:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="input-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">Ingresar</button>
        </form>

        <a href="register.php" class="register-link">➕ Crear Cuenta</a>
    </div>
</body>
</html>
