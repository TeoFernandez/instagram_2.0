<?php
session_start();
include("includes/conexion.php");

$id_post = $_POST["id_post"];
$id_usuario = $_SESSION["usuario"]["id"];
$tipo = $_POST["tipo"] ?? 'like';

// Si ya reaccionó, ignoramos
$stmt = $conn->prepare("SELECT * FROM reacciones WHERE id_post = ? AND id_usuario = ?");
$stmt->execute([$id_post, $id_usuario]);

if ($stmt->rowCount() == 0) {
    $stmt = $conn->prepare("INSERT INTO reacciones (id_post, id_usuario, tipo) VALUES (?, ?, ?)");
    $stmt->execute([$id_post, $id_usuario, $tipo]);
}

// Obtener dueño del post
$stmt_post = $conn->prepare("SELECT id_usuario FROM posts WHERE id = ?");
$stmt_post->execute([$id_post]);
$duenio = $stmt_post->fetch(PDO::FETCH_ASSOC);

if ($duenio && $duenio["id_usuario"] != $id_usuario) {
    $msg = "Reaccionaron a tu publicación.";
    $stmt_noti = $conn->prepare("INSERT INTO notificaciones (id_usuario, tipo, mensaje) VALUES (?, 'reaccion', ?)");
    $stmt_noti->execute([$duenio["id_usuario"], $msg]);
}


header("Location: ver_posts.php");
exit;
