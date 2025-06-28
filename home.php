<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}
?>

<h2>¡Hola, <?= $_SESSION["usuario"]["nombre"] ?>!</h2>
<a href="logout.php">Cerrar sesión</a>
