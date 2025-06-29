<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}
?>

<h2>¡Hola, <?= $_SESSION["usuario"]["nombre"] ?>!</h2>
<a href="logout.php">Cerrar sesión</a>
<form action="buscar.php" method="GET">
    <input type="text" name="q" placeholder="Buscar tecnologías...">
    <button type="submit">🔍</button>
</form>
<a href="guardados.php">📂 Mis guardados</a>
<?php
$stmt = $conn->prepare("SELECT COUNT(*) FROM notificaciones WHERE id_usuario = ? AND leida = 0");
$stmt->execute([$_SESSION["usuario"]["id"]]);
$cant = $stmt->fetchColumn();
?>

<a href="notificaciones.php">🔔 Notificaciones (<?= $cant ?>)</a>

