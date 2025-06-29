<?php
if (!isset($_SESSION)) session_start();
?>

<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/base.css">

<style>
.navbar {
    background-color: #1b263b;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 24px;
}

.navbar a {
    color: #ffffff;
    text-decoration: none;
    margin-left: 16px;
    font-weight: 600;
}

.navbar a:hover {
    color: #1b9aaa;
}

.navbar .left {
    font-size: 1.3em;
}

.navbar .right {
    display: flex;
    align-items: center;
}

@media (max-width: 600px) {
    .navbar {
        flex-direction: column;
        align-items: flex-start;
    }

    .navbar .right {
        flex-wrap: wrap;
    }
}
</style>

<div class="navbar">
    <div class="left">
        <a href="home.php">🧠 Social DEV TyN</a>
    </div>
    <div class="right">
        <a href="ver_posts.php">Feed</a>
        <a href="perfil.php">Mi Perfil</a>
        <a href="notificaciones.php">Notificaciones</a>
        <a href="logout.php">Salir</a>
    </div>
</div>
