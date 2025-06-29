<?php
session_start();
include("includes/conexion.php");

$id_post = $_POST["id_post"];
$id_usuario = $_SESSION["usuario"]["id"];

// Verificar si ya lo guardó
$stmt = $conn->prepare("SELECT * FROM guardados WHERE id_usuario = ? AND id_post = ?");
$stmt->execute([$id_usuario, $id_post]);

if ($stmt->rowCount() > 0) {
    // Si ya existe, eliminar (desguardar)
    $conn->prepare("DELETE FROM guardados WHERE id_usuario = ? AND id_post = ?")->execute([$id_usuario, $id_post]);
} else {
    // Si no existe, guardar
    $conn->prepare("INSERT INTO guardados (id_usuario, id_post) VALUES (?, ?)")->execute([$id_usuario, $id_post]);
}

header("Location: ver_posts.php");
exit;
