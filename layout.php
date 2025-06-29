<!-- layout.php -->
<?php if (!isset($titulo)) $titulo = "Social DEV TyN"; ?>
<?php if (!isset($contenido)) $contenido = ""; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Tipografía -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/estilos.css"> <!-- opcional externo -->

    <style>
    body {
        background-color: #0d1b2a;
        color: #ffffff;
        font-family: 'Space Grotesk', sans-serif;
    }

    .card {
        background-color: #1b263b;
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        margin-bottom: 20px;
    }

    .btn-primary {
        background-color: #1b9aaa;
        border: none;
    }

    .btn-primary:hover {
        background-color: #148a94;
    }

    .navbar {
        background-color: #1b263b;
    }

    .navbar-brand {
        font-weight: 600;
        color: #ffffff;
    }

    .fab-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #1b9aaa;
        color: white;
        border-radius: 50%;
        font-size: 24px;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
        text-decoration: none;
    }

    .fab-button:hover {
        background-color: #148a94;
    }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="home.php">Social DEV TyN</a>
        </div>
    </nav>

    <main class="container mt-4">
        <?= $contenido ?>
    </main>
</body>
</html>
