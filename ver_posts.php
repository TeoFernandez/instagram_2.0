<?php
session_start();
include("includes/conexion.php");

$posts = $conn->query("
    SELECT p.*, u.nombre, u.apellido 
    FROM posts p 
    JOIN usuarios u ON p.id_usuario = u.id 
    ORDER BY p.fecha_post DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Social DEV TyN | Publicaciones</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/ver_posts.css">
</head>
<body>
<div class="container">
    <h2>🧠 Social DEV TyN - Feed</h2>
    <a href="crear_post.php" class="btn btn-guardar">➕ Crear nueva publicación</a>
    <a href="logout.php" class="btn btn-like">Cerrar sesión</a>
    <hr>

    <?php foreach ($posts as $post): ?>
        <div class="card-post">
            <div>
                <strong><?= $post["nombre"] . " " . $post["apellido"] ?></strong>
                <small style="float:right;"><?= $post["fecha_post"] ?></small>
            </div>
            <div class="comentario"><?= $post["comentario"] ?></div>
            <img src="<?= $post["imagen"] ?>" style="
                <?= ($post["filtro"] == "grayscale") ? 'filter: grayscale(100%);' : '' ?>
                <?= ($post["filtro"] == "sepia") ? 'filter: sepia(100%);' : '' ?>
                <?= ($post["filtro"] == "blur") ? 'filter: blur(2px);' : '' ?>">
            
            <div class="acciones">
                <form method="POST" action="reaccionar.php" style="display:inline;">
                    <input type="hidden" name="id_post" value="<?= $post['id'] ?>">
                    <button class="btn btn-like" name="tipo" value="like">👍 Me gusta</button>
                </form>

                <form method="POST" action="guardar.php" style="display:inline;">
                    <input type="hidden" name="id_post" value="<?= $post['id'] ?>">
                    <button class="btn btn-guardar">📌 Guardar</button>
                </form>
            </div>

            <!-- Comentarios -->
            <div class="comentarios">
                <strong>Comentarios:</strong>
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
                    <div>
                        <strong><?= $comentario['nombre'] ?>:</strong> <?= $comentario['texto'] ?><br>
                        <?php if ($comentario['imagen']): ?>
                            <img src="<?= $comentario['imagen'] ?>" style="width:150px; margin-top:5px;">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Formulario para comentar -->
            <form method="POST" action="comentar.php" enctype="multipart/form-data">
                <input type="hidden" name="id_post" value="<?= $post['id'] ?>">
                <textarea name="texto" placeholder="Escribí un comentario..."></textarea>
                <input type="file" name="imagen_comentario">
                <button class="btn btn-guardar" type="submit">Comentar</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
