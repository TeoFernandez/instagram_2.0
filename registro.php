<?php
include("includes/conexion.php");

$errores = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $email = trim($_POST["email"]);
    $contraseña = $_POST["contraseña"];

    // Validación
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/', $contraseña)) {
        $errores = "La contraseña debe tener entre 8 y 16 caracteres, incluir una mayúscula y un número.";
    } else {
        // Hash + Insert
        $hash = password_hash($contraseña, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, email, contraseña) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$nombre, $apellido, $email, $hash]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $errores = "Error: el correo ya está registrado.";
        }
    }
}
?>

<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="apellido" placeholder="Apellido" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="contraseña" placeholder="Contraseña" required>
    <button type="submit">Registrarse</button>
    <p style="color:red"><?= $errores ?></p>
</form>
