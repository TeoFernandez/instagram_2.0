<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}
include("includes/conexion.php");

$id_usuario = $_SESSION["usuario"]["id"];

// Obtener notificaciones
$stmt = $conn->prepare("SELECT * FROM notificaciones WHERE id_usuario = ? ORDER BY fecha DESC");
$stmt->execute([$id_usuario]);
$notis = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Marcar como leídas
$conn->prepare("UPDATE notificaciones SET leida = 1 WHERE id_usuario = ?")->execute([$id_usuario]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificaciones - Social DEV TyN</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/notificaciones.css">
</head>
<body>

<?php include("includes/navbar.php"); ?>

<div class="container noti-container">
    <h2>🔔 Mis notificaciones</h2>

    <?php if (empty($notis)): ?>
        <p style="text-align:center; margin-top:30px;">Todavía no tenés notificaciones.</p>
    <?php else: ?>
        <?php foreach ($notis as $n): ?>
            <div class="noti-card <?= $n["leida"] ? 'leida' : '' ?>">
                <?= htmlspecialchars($n["mensaje"]) ?>
                <small><?= $n["fecha"] ?></small>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>

