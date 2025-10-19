<?php
function carregar_categoria($arquivo_json, $limite = 7) {
    if (file_exists($arquivo_json) && filesize($arquivo_json) > 0) { 
        $dados = json_decode(file_get_contents($arquivo_json), true);
        return is_array($dados) ? array_slice($dados, 0, $limite) : [];
    } return []; 
}
$restaurantes = carregar_categoria('data/restaurantes.json');
$bares = carregar_categoria('data/bares.json');
$baladas = carregar_categoria('data/baladas.json');
$espacos_kids = carregar_categoria('data/espacos_kids.json');
$eventos = carregar_categoria('data/eventos.json');
$mapa = carregar_categoria('data/mapa.json');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ColatinaMais - Descubra a Cidade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Damion&family=Montserrat:wght@400&family=Roboto:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <header class="top-header fixed-top text-white d-flex justify-content-between align-items-center p-3">
        <h1 class="logo mb-0">COLATINA <span class="text-warning">+</span></h1>
        <div class="d-flex align-items-center">
             <a href="#" class="text-white me-3" id="search-button" title="Pesquisar"><i class="bi bi-search header-icon"></i></a>
             <a href="admin/login.php" class="text-white" title="Acesso do Administrador"><i class="bi bi-person-circle admin-login-icon header-icon"></i></a>
        </div>
    </header>
    <main class="main-content">
     <a href="list.php" style="text-decoration: none; color: inherit;">
         
         
         
    <section 
        class="hero-section text-white text-center d-flex flex-column justify-content-center align-items-center"
        style="background-image: url('https://colatina1.goodbarber.app/apiv3/photo/iphone/compilation_images_ipadLandscapeDefault@original.jpg?v=1760008499');
               background-size: cover;
               background-position: center;
               background-repeat: no-repeat;
              
               position: relative;">
               
        <div class="hero-overlay"></div>

        <div class="container" style="z-index: 2;">
            <h2 class="display-2 fw-bold mb-5 "></h2>
            <p class="lead"></p>
        </div>
    </section>
</a>



        <?php function criar_secao($titulo, $dados) { if (!empty($dados)) { echo "<section class='container-fluid mt-4'><h3 class='h5 mb-2 px-2'>$titulo</h3><div class='horizontal-scroll-wrapper'>"; foreach ($dados as $item) { echo "<a href='details.php?id=".htmlspecialchars($item['id'])."' class='scroll-card'><img src='".htmlspecialchars($item['imagem'])."' class='card-img-top' alt='".htmlspecialchars($item['nome'])."'><div class='card-body'><h6 class='card-title'>".htmlspecialchars($item['nome'])."</h6></div></a>"; } echo "</div></section>"; } }
        criar_secao("Restaurantes", $restaurantes);
        criar_secao("Bares", $bares);
        criar_secao("Baladas", $baladas);
        criar_secao("Espaços Kids", $espacos_kids);
        criar_secao("Eventos", $eventos);
        criar_secao("Mapa (Pontos Turísticos)", $mapa);
        
        
        ?>
    </main>
    </main> <div class="search-overlay" id="search-overlay">
        <div class="search-container">
            <div class="search-header">
                <input type="text" id="search-input" placeholder="O que você procura?" class="form-control">
                <a href="#" id="close-search" class="close-search-btn"><i class="bi bi-x-lg"></i></a>
            </div>
            <div id="search-results" class="search-results">
                </div>
        </div>
    </div>
    <nav class="bottom-nav fixed-bottom text-white d-flex justify-content-around align-items-center py-2 shadow-lg">
        <a href="index.php" class="bottom-nav-item" data-nav-action="home"><i class="bi bi-house-fill d-block mb-1"></i>Início</a>
        <a href="favorites.php" class="bottom-nav-item" data-nav-action="favoritos"><i class="bi bi-heart-fill d-block mb-1"></i>Favoritos</a>
        <a href="list.php" class="bottom-nav-item" data-nav-action="categorias"><i class="bi bi-grid-fill d-block mb-1"></i>Categorias</a>
    </nav>
    <script src="assets/js/main.js"></script> 
</body>
</html>