<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}
include("includes/conexion.php");

// Obtener los posts con datos del usuario que los publicó
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

<form action="buscar.php" method="GET">
    <input type="text" name="q" placeholder="Buscar tecnologías...">
    <button type="submit">🔍</button>
</form>

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

        <!-- Reaccionar al post -->
        <form method="POST" action="reaccionar.php" style="margin-top:10px;">
            <input type="hidden" name="id_post" value="<?= $post['id'] ?>">
            <button type="submit" name="tipo" value="like">👍 Me gusta</button>
        </form>

        <!-- Guardar / Desguardar -->
        <form method="POST" action="guardar.php" style="display:inline;">
            <input type="hidden" name="id_post" value="<?= $post['id'] ?>">
            <button type="submit">
                <?php
                // Ver si ya está guardado
                $stmt = $conn->prepare("SELECT * FROM guardados WHERE id_usuario = ? AND id_post = ?");
                $stmt->execute([$_SESSION["usuario"]["id"], $post["id"]]);
                echo $stmt->rowCount() > 0 ? "🔖 Guardado" : "📌 Guardar";
                ?>
            </button>
        </form>


        <!-- Formulario de comentario -->
        <form method="POST" action="comentar.php" enctype="multipart/form-data" style="margin-top:10px;">
            <input type="hidden" name="id_post" value="<?= $post['id'] ?>">
            <textarea name="texto" placeholder="Escribí un comentario..." rows="2" cols="50"></textarea><br>
            <input type="file" name="imagen_comentario"><br>
            <button type="submit">Comentar</button>
        </form>

        <!-- Comentarios -->
        <div style="margin-top:15px; padding-left:15px;">
            <strong>Comentarios:</strong><br>
            <?php
            $comentarios = $conn->prepare("
                SELECT c.*, u.nombre 
                FROM comentarios c 
                JOIN usuarios u ON c.id_usuario = u.id 
                WHERE c.id_post = ? 
                ORDER BY c.fecha_comentario ASC
            ");
            $comentarios->execute([$post['id']]);
            foreach ($comentarios as $comentario):
            ?>
                <div style="margin-top:10px;">
                    <strong><?= $comentario['nombre'] ?>:</strong> <?= $comentario['texto'] ?><br>
                    <?php if ($comentario['imagen']): ?>
                        <img src="<?= $comentario['imagen'] ?>" style="width:150px;">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>
