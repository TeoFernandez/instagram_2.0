<?php
include("includes/conexion.php");

$id_usuario = $_GET["id"] ?? null;

if (!$id_usuario) {
    echo "Perfil no encontrado.";
    exit;
}

// Obtener datos del usuario y su perfil
$stmt = $conn->prepare("SELECT u.nombre, u.apellido, p.* FROM usuarios u LEFT JOIN perfiles p ON u.id = p.id_usuario WHERE u.id = ?");
$stmt->execute([$id_usuario]);
$perfil = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$perfil) {
    echo "Perfil no encontrado.";
    exit;
}
?>

<h2><?= $perfil['nombre'] . ' ' . $perfil['apellido'] ?></h2>

<?php if ($perfil['foto_perfil']): ?>
    <img src="<?= $perfil['foto_perfil'] ?>" style="width:100px;"><br>
<?php endif; ?>

<p><strong>Rol:</strong> <?= $perfil['rol'] ?></p>
<p><strong>Tecnologías:</strong> <?= $perfil['tecnologias'] ?></p>
<p><strong>Experiencia:</strong> <?= $perfil['experiencia'] ?></p>
<p><strong>Bio:</strong> <?= $perfil['bio'] ?></p>

<p><strong>GitHub:</strong> <a href="<?= $perfil['github'] ?>" target="_blank"><?= $perfil['github'] ?></a></p>
<p><strong>Portfolio:</strong> <a href="<?= $perfil['portfolio'] ?>" target="_blank"><?= $perfil['portfolio'] ?></a></p>
<p><strong>StackOverflow:</strong> <a href="<?= $perfil['stackoverflow'] ?>" target="_blank"><?= $perfil['stackoverflow'] ?></a></p>
