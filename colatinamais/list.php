<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - ColatinaMais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Damion&family=Montserrat:wght@400;600&family=Roboto:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <header class="top-header fixed-top text-white d-flex justify-content-between align-items-center p-3">
        <h1 class="logo mb-0">Categorias</h1>
        <a href="admin/login.php" class="text-white" title="Acesso do Administrador"><i class="bi bi-person-circle admin-login-icon header-icon"></i></a>
    </header>
    <main class="main-content container pt-3">
        <div class="row g-3">
            <div class="col-6"><a href="category.php?tipo=restaurante" class="category-menu-item" style="background-color: #3D51EB;"><i class="bi bi-cup-hot"></i><span>Restaurantes</span></a></div>
            <div class="col-6"><a href="category.php?tipo=bar" class="category-menu-item" style="background-color: #EC693D;"><i class="bi bi-tropical-storm"></i><span>Bares</span></a></div>
            <div class="col-6"><a href="category.php?tipo=balada" class="category-menu-item" style="background-color: #3D40EB;"><i class="bi bi-music-note-beamed"></i><span>Baladas</span></a></div>
            <div class="col-6"><a href="category.php?tipo=espaco_kids" class="category-menu-item" style="background-color: #3D51EB;"><i class="bi bi-emoji-smile"></i><span>Espaços Kids</span></a></div>
            <div class="col-6"><a href="category.php?tipo=evento" class="category-menu-item" style="background-color: #3D40EB;"><i class="bi bi-calendar-event"></i><span>Eventos</span></a></div>
            <div class="col-6"><a href="category.php?tipo=mapa" class="category-menu-item" style="background-color: #EC693D;"><i class="bi bi-map"></i><span>Mapa</span></a></div>
        </div>
    </main>
    <nav class="bottom-nav fixed-bottom text-white d-flex justify-content-around align-items-center py-2 shadow-lg">
        <a href="index.php" class="bottom-nav-item" data-nav-action="home"><i class="bi bi-house-fill d-block mb-1"></i>Início</a>
        <a href="favorites.php" class="bottom-nav-item" data-nav-action="favoritos"><i class="bi bi-heart-fill d-block mb-1"></i>Favoritos</a>
        <a href="list.php" class="bottom-nav-item active" data-nav-action="categorias"><i class="bi bi-grid-fill d-block mb-1"></i>Categorias</a>
    </nav>
    <script src="assets/js/main.js"></script> 
</body>
</html>