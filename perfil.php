<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

include("includes/conexion.php");

$id_usuario = $_SESSION["usuario"]["id"];
$mensaje = "";

// Si ya tiene perfil, lo traemos
$stmt = $conn->prepare("SELECT * FROM perfiles WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$perfil = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rol = $_POST["rol"];
    $tecnologias = $_POST["tecnologias"];
    $experiencia = $_POST["experiencia"];
    $github = $_POST["github"];
    $portfolio = $_POST["portfolio"];
    $stackoverflow = $_POST["stackoverflow"];
    $bio = $_POST["bio"];
    $foto_perfil = "";

    // Si subió una foto
    if (!empty($_FILES["foto"]["tmp_name"])) {
        $tipo = $_FILES["foto"]["type"];
        $tamaño = $_FILES["foto"]["size"];

        if (in_array($tipo, ["image/jpeg", "image/png"]) && $tamaño <= 5 * 1024 * 1024) {
            $foto_perfil = "uploads/perfil_" . time() . "_" . basename($_FILES["foto"]["name"]);
            move_uploaded_file($_FILES["foto"]["tmp_name"], $foto_perfil);
        }
    }

    if ($perfil) {
        // UPDATE
        $stmt = $conn->prepare("UPDATE perfiles SET rol=?, tecnologias=?, experiencia=?, github=?, portfolio=?, stackoverflow=?, bio=?, foto_perfil=? WHERE id_usuario=?");
        $stmt->execute([$rol, $tecnologias, $experiencia, $github, $portfolio, $stackoverflow, $bio, $foto_perfil ?: $perfil["foto_perfil"], $id_usuario]);
        $mensaje = "Perfil actualizado con éxito.";
    } else {
        // INSERT
        $stmt = $conn->prepare("INSERT INTO perfiles (id_usuario, rol, tecnologias, experiencia, github, portfolio, stackoverflow, bio, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_usuario, $rol, $tecnologias, $experiencia, $github, $portfolio, $stackoverflow, $bio, $foto_perfil]);
        $mensaje = "Perfil creado con éxito.";
    }

    // Recargar perfil actualizado
    $stmt = $conn->prepare("SELECT * FROM perfiles WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h2>Mi Perfil Profesional IT</h2>
<p style="color:green"><?= $mensaje ?></p>

<form method="POST" enctype="multipart/form-data">
    <label>Rol:</label><br>
    <input type="text" name="rol" value="<?= $perfil['rol'] ?? '' ?>"><br><br>

    <label>Tecnologías (separadas por coma):</label><br>
    <input type="text" name="tecnologias" value="<?= $perfil['tecnologias'] ?? '' ?>"><br><br>

    <label>Experiencia:</label><br>
    <textarea name="experiencia" rows="3"><?= $perfil['experiencia'] ?? '' ?></textarea><br><br>

    <label>GitHub:</label><br>
    <input type="url" name="github" value="<?= $perfil['github'] ?? '' ?>"><br>

    <label>Portfolio:</label><br>
    <input type="url" name="portfolio" value="<?= $perfil['portfolio'] ?? '' ?>"><br>

    <label>StackOverflow:</label><br>
    <input type="url" name="stackoverflow" value="<?= $perfil['stackoverflow'] ?? '' ?>"><br><br>

    <label>Biografía:</label><br>
    <textarea name="bio" rows="2"><?= $perfil['bio'] ?? '' ?></textarea><br><br>

    <label>Foto de perfil:</label><br>
    <input type="file" name="foto"><br>
    <?php if (!empty($perfil["foto_perfil"])): ?>
        <img src="<?= $perfil["foto_perfil"] ?>" style="width:100px;"><br>
    <?php endif; ?><br>

    <button type="submit">Guardar</button>
</form>
