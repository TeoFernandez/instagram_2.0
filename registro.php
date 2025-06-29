<?php
include("includes/conexion.php");

$errores = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $email = trim($_POST["email"]);
    $contraseña = $_POST["contraseña"];

    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/', $contraseña)) {
        $errores = "❌ La contraseña debe tener entre 8 y 16 caracteres, incluir una mayúscula y un número.";
    } else {
        $hash = password_hash($contraseña, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, email, contraseña) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$nombre, $apellido, $email, $hash]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $errores = "❌ Ese correo ya está registrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse - Social DEV TyN</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/auth.css">

</head>
<body>

    <div class="auth-container">
        <h2>Crear cuenta</h2>

        <?php if ($errores): ?>
        <div class="error"><?= $errores ?></div>
        <?php endif; ?>

        <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="contraseña" placeholder="Contraseña" required>
        <button type="submit">Registrarse</button>
        </form>

        <p>¿Ya tenés cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
</body>
</html>
