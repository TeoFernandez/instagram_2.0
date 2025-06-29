<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

include("includes/conexion.php");
include("includes/navbar.php");

// Obtener cantidad de notificaciones sin leer
$stmt = $conn->prepare("SELECT COUNT(*) FROM notificaciones WHERE id_usuario = ? AND leida = 0");
$stmt->execute([$_SESSION["usuario"]["id"]]);
$cant = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Social DEV TyN</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>

<div class="container home-container">
    <h2>👋 ¡Hola, <?= htmlspecialchars($_SESSION["usuario"]["nombre"]) ?>!</h2>

    <form class="search-form" action="buscar.php" method="GET">
        <input type="text" name="q" placeholder="Buscar tecnologías...">
        <button type="submit">🔍 Buscar</button>
    </form>

    <div class="links">
        <a href="ver_posts.php">📝 Ir al Feed</a>
        <a href="perfil.php">👤 Editar mi perfil</a>
        <a href="guardados.php">📂 Ver publicaciones guardadas</a>
        <a href="notificaciones.php">🔔 Notificaciones (<?= $cant ?>)</a>
        <a href="logout.php">🚪 Cerrar sesión</a>
    </div>
</div>

</body>
</html>

