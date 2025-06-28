<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}
include("includes/conexion.php");

$posts = $conn->query("
    SELECT p.*, u.nombre, u.apellido 
    FROM posts p 
    JOIN usuarios u ON p.id_usuario = u.id 
    ORDER BY p.fecha_post DESC
");
?>

<h2>Publicaciones recientes</h2>
<a href="crear_post.php">Crear nueva publicación</a> | 
<a href="logout.php">Cerrar sesión</a><br><br>

<?php foreach ($posts as $post): ?>
    <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
        <strong><?= $post["nombre"] . " " . $post["apellido"] ?></strong><br>
        <small><?= $post["fecha_post"] ?></small><br><br>
        <p><?= $post["comentario"] ?></p>
        <img src="<?= $post["imagen"] ?>" 
            style="width:300px; 
                    <?= ($post["filtro"] == "grayscale" ? 'filter: grayscale(100%);' : '') ?>
                    <?= ($post["filtro"] == "sepia" ? 'filter: sepia(100%);' : '') ?>
                    <?= ($post["filtro"] == "blur" ? 'filter: blur(2px);' : '') ?>">
    </div>
<?php endforeach; ?>
