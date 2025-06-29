<?php
session_start();
include("includes/conexion.php");

$busqueda = $_GET["q"] ?? "";

$resultados = [];

if ($busqueda) {
    // Buscar en posts
    $stmt1 = $conn->prepare("SELECT * FROM posts WHERE etiquetas LIKE ?");
    $stmt1->execute(["%$busqueda%"]);
    $resultados["posts"] = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Buscar en perfiles
    $stmt2 = $conn->prepare("SELECT u.nombre, u.apellido, p.* FROM perfiles p JOIN usuarios u ON p.id = p.id_usuario WHERE p.tecnologias LIKE ?");
    $stmt2->execute(["%$busqueda%"]);
    $resultados["perfiles"] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2>Buscar contenido IT</h2>
<form method="GET">
    <input type="text" name="q" placeholder="Buscar tecnología..." value="<?= htmlspecialchars($busqueda) ?>">
    <button type="submit">Buscar</button>
</form>

<?php if ($busqueda): ?>
    <h3>Resultados para: "<?= htmlspecialchars($busqueda) ?>"</h3>

    <h4>📝 Posts:</h4>
    <?php foreach ($resultados["posts"] as $post): ?>
        <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
            <strong><?= $post["comentario"] ?></strong><br>
            <small>Etiquetas: <?= $post["etiquetas"] ?></small><br>
            <?php if ($post["imagen"]): ?>
                <img src="<?= $post["imagen"] ?>" style="width:200px;">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <h4>👤 Perfiles:</h4>
    <?php foreach ($resultados["perfiles"] as $perfil): ?>
        <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
            <strong><?= $perfil["nombre"] . " " . $perfil["apellido"] ?></strong><br>
            <small>Tecnologías: <?= $perfil["tecnologias"] ?></small><br>
            <a href="ver_perfil.php?id=<?= $perfil["id_usuario"] ?>">Ver perfil</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
