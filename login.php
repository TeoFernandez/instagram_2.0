<?php
session_start();
include("includes/conexion.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contraseña, $usuario["contraseña"])) {
        $_SESSION["usuario"] = $usuario;
        header("Location: home.php");
        exit;
    } else {
        $error = "❌ Credenciales incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - Social DEV TyN</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

    <center><h1>Social DEV TyN</h1></center>
    <center><p>Conectate con tus amigos Desarrolladores</p></center>
    <div class="auth-container">
        <h2>Iniciar sesión</h2>

        <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="contraseña" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>

        <p>¿No tenés cuenta? <a href="registro.php">Registrate</a></p>
    </div>

    <footer>
        <center><p>© 2025 Social DEV TyN. Todos los derechos reservados.
        Desarrollado por Teo Fernández y Nicolás Garcia.</p></center>
    </footer>
</body>
</html>
