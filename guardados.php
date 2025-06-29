<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}
include("includes/conexion.php");

$id_usuario = $_SESSION["usuario"]["id"];

// Obtener los posts guardados por el usuario
$stmt = $conn->prepare("
    SELECT p.*, u.nombre, u.apellido 
    FROM guardados g
    JOIN posts p ON g.id_post = p.id
    JOIN usuarios u ON p.id_usuario = u.id
    WHERE g.id_usuario = ?
    ORDER BY g.fecha_guardado DESC
");
$stmt->execute([$id_usuario]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>📂 Mis publicaciones guardadas</h2>
<a href="ver_posts.php">← Volver a posts</a><br><br>

<?php foreach ($posts as $post): ?>
    <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
        <strong><?= $post["nombre"] . " " . $post["apellido"] ?></strong><br>
        <small><?= $post["fecha_post"] ?></small><br><br>
        <p><?= $post["comentario"] ?></p>
        <img src="<?= $post["imagen"] ?>" style="width:300px; 
            <?= ($post["filtro"] == "grayscale" ? 'filter: grayscale(100%);' : '') ?>
            <?= ($post["filtro"] == "sepia" ? 'filter: sepia(100%);' : '') ?>
            <?= ($post["filtro"] == "blur" ? 'filter: blur(2px);' : '') ?>">
    </div>
<?php endforeach; ?>
