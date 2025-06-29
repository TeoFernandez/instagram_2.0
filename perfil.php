<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

include("includes/conexion.php");

$id_usuario = $_SESSION["usuario"]["id"];
$mensaje = "";

// Buscar perfil actual
$stmt = $conn->prepare("SELECT * FROM perfiles WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$perfil = $stmt->fetch(PDO::FETCH_ASSOC);

// Guardar o actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rol = $_POST["rol"];
    $tecnologias = $_POST["tecnologias"];
    $experiencia = $_POST["experiencia"];
    $github = $_POST["github"];
    $portfolio = $_POST["portfolio"];
    $stackoverflow = $_POST["stackoverflow"];
    $bio = $_POST["bio"];
    $foto_perfil = "";

    if (!empty($_FILES["foto"]["tmp_name"])) {
        $tipo = $_FILES["foto"]["type"];
        $tamaño = $_FILES["foto"]["size"];

        if (in_array($tipo, ["image/jpeg", "image/png"]) && $tamaño <= 5 * 1024 * 1024) {
            $foto_perfil = "uploads/perfil_" . time() . "_" . basename($_FILES["foto"]["name"]);
            move_uploaded_file($_FILES["foto"]["tmp_name"], $foto_perfil);
        }
    }

    if ($perfil) {
        $stmt = $conn->prepare("UPDATE perfiles SET rol=?, tecnologias=?, experiencia=?, github=?, portfolio=?, stackoverflow=?, bio=?, foto_perfil=? WHERE id_usuario=?");
        $stmt->execute([$rol, $tecnologias, $experiencia, $github, $portfolio, $stackoverflow, $bio, $foto_perfil ?: $perfil["foto_perfil"], $id_usuario]);
        $mensaje = "✅ Perfil actualizado con éxito.";
    } else {
        $stmt = $conn->prepare("INSERT INTO perfiles (id_usuario, rol, tecnologias, experiencia, github, portfolio, stackoverflow, bio, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_usuario, $rol, $tecnologias, $experiencia, $github, $portfolio, $stackoverflow, $bio, $foto_perfil]);
        $mensaje = "✅ Perfil creado con éxito.";
    }

    // Recargar perfil
    $stmt = $conn->prepare("SELECT * FROM perfiles WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Social DEV TyN</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/perfil.css">
</head>
<body>
    <?php include("includes/navbar.php"); ?>

<div class="container">
    <h2>👤 Mi Perfil Profesional</h2>
    <p style="color:lightgreen"><?= $mensaje ?></p>

    <div class="card-perfil">
        <form method="POST" enctype="multipart/form-data">
            <label>Rol profesional</label>
            <input type="text" name="rol" value="<?= $perfil['rol'] ?? '' ?>">

            <label>Tecnologías (separadas por coma)</label>
            <input type="text" name="tecnologias" value="<?= $perfil['tecnologias'] ?? '' ?>">

            <label>Experiencia</label>
            <textarea name="experiencia" rows="3"><?= $perfil['experiencia'] ?? '' ?></textarea>

            <label>GitHub</label>
            <input type="url" name="github" value="<?= $perfil['github'] ?? '' ?>">

            <label>Portfolio</label>
            <input type="url" name="portfolio" value="<?= $perfil['portfolio'] ?? '' ?>">

            <label>StackOverflow</label>
            <input type="url" name="stackoverflow" value="<?= $perfil['stackoverflow'] ?? '' ?>">

            <label>Biografía</label>
            <textarea name="bio" rows="2"><?= $perfil['bio'] ?? '' ?></textarea>

            <label>Foto de perfil</label>
            <input type="file" name="foto">

            <?php if (!empty($perfil["foto_perfil"])): ?>
                <img class="foto-preview" src="<?= $perfil["foto_perfil"] ?>" alt="Foto de perfil">
            <?php endif; ?>

            <br><br>
            <button type="submit" class="btn btn-like">Guardar Perfil</button>
        </form>
    </div>
</div>
</body>
</html>
