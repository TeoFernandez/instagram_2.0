<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

include("includes/conexion.php");
$errores = "";

$etiquetas = $_POST["etiquetas"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comentario = $_POST["comentario"];
    $filtro = $_POST["filtro"];
    $imagen_nombre = $_FILES["imagen"]["name"];
    $imagen_temp = $_FILES["imagen"]["tmp_name"];
    $imagen_tipo = $_FILES["imagen"]["type"];
    $imagen_tamaño = $_FILES["imagen"]["size"];

    // Validar imagen
    $extensiones_permitidas = ["image/jpeg", "image/png"];
    if (!in_array($imagen_tipo, $extensiones_permitidas)) {
        $errores = "Solo se permiten imágenes JPG o PNG.";
    } elseif ($imagen_tamaño > 5 * 1024 * 1024) {
        $errores = "La imagen no debe superar los 5MB.";
    } else {
        $ruta = "uploads/" . time() . "_" . basename($imagen_nombre);
        move_uploaded_file($imagen_temp, $ruta);

        $stmt = $conn->prepare("INSERT INTO posts (id_usuario, comentario, imagen, filtro, etiquetas) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION["usuario"]["id"], $comentario, $ruta, $filtro, $etiquetas]);

        header("Location: ver_posts.php");
        exit;
    }
}
?>

<h2>Crear publicación</h2>

<form method="POST" enctype="multipart/form-data">
    <textarea name="comentario" placeholder="Escribí un comentario..." required></textarea><br>
    
    <label>Seleccioná un filtro:</label>
    <label>Etiquetas (separadas por coma):</label><br>
    <input type="text" name="etiquetas" placeholder="ej: php, mysql, backend"><br><br>
    <select name="filtro">
        <option value="none">Ninguno</option>
        <option value="grayscale">Blanco y negro</option>
        <option value="sepia">Sepia</option>
        <option value="blur">Desenfocado</option>
    </select><br><br>

    <input type="file" name="imagen" required><br><br>
    <button type="submit">Publicar</button>

    <p style="color:red"><?= $errores ?></p>
</form>
