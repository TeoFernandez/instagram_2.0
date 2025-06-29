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

<h2>🔔 Mis notificaciones</h2>
<a href="ver_posts.php">← Volver a publicaciones</a><br><br>

<?php if (empty($notis)): ?>
    <p>No tenés notificaciones por ahora.</p>
<?php else: ?>
    <?php foreach ($notis as $n): ?>
        <div style="border-left: 4px solid <?= $n['leida'] ? '#ccc' : 'blue' ?>; padding: 8px; margin-bottom: 8px;">
            <?= $n["mensaje"] ?> <br>
            <small><?= $n["fecha"] ?></small>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
