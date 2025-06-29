<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear publicación - Social DEV TyN</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/crear_post.css">
</head>
<body>

<?php include("includes/navbar.php"); ?>

<div class="post-form-container">
    <h2>📝 Crear publicación</h2>

    <form method="POST" enctype="multipart/form-data">
        <label for="comentario">Comentario</label>
        <textarea name="comentario" id="comentario" placeholder="Escribí un comentario..." required></textarea>

        <label for="etiquetas">Etiquetas (separadas por coma)</label>
        <input type="text" name="etiquetas" id="etiquetas" placeholder="Ej: php, backend, mysql">

        <label for="filtro">Filtro de imagen</label>
        <select name="filtro" id="filtro">
            <option value="none">Ninguno</option>
            <option value="grayscale">Blanco y negro</option>
            <option value="sepia">Sepia</option>
            <option value="blur">Desenfocado</option>
        </select>

        <label for="imagen">Seleccioná una imagen</label>
        <input type="file" name="imagen" id="imagen" required>

        <button type="submit">Publicar</button>

        <?php if (!empty($errores)): ?>
            <div class="error"><?= $errores ?></div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
