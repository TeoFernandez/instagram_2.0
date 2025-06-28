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

header("Location: ver_posts.php");
exit;
