<?php
session_start();
include("includes/conexion.php");

$id_post = $_POST["id_post"];
$id_usuario = $_SESSION["usuario"]["id"];
$texto = $_POST["texto"];
$imagen = "";

if (!empty($_FILES["imagen_comentario"]["tmp_name"])) {
    $tipo = $_FILES["imagen_comentario"]["type"];
    $tamaño = $_FILES["imagen_comentario"]["size"];

    if (in_array($tipo, ["image/jpeg", "image/png"]) && $tamaño <= 5 * 1024 * 1024) {
        $ruta = "uploads/" . time() . "_" . basename($_FILES["imagen_comentario"]["name"]);
        move_uploaded_file($_FILES["imagen_comentario"]["tmp_name"], $ruta);
        $imagen = $ruta;
    }
}

$stmt = $conn->prepare("INSERT INTO comentarios (id_post, id_usuario, texto, imagen) VALUES (?, ?, ?, ?)");
$stmt->execute([$id_post, $id_usuario, $texto, $imagen]);

// Obtener dueño del post
$stmt_post = $conn->prepare("SELECT id_usuario FROM posts WHERE id = ?");
$stmt_post->execute([$id_post]);
$duenio = $stmt_post->fetch(PDO::FETCH_ASSOC);

if ($duenio && $duenio["id_usuario"] != $id_usuario) {
    $msg = "Comentaron tu publicación.";
    $stmt_noti = $conn->prepare("INSERT INTO notificaciones (id_usuario, tipo, mensaje) VALUES (?, 'comentario', ?)");
    $stmt_noti->execute([$duenio["id_usuario"], $msg]);
}


header("Location: ver_posts.php");
exit;
