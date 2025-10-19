<?php
function carregar_todas_atracoes() {
    // ✅ INCLUI TODOS OS ARQUIVOS JSON
    $arquivos = ['data/restaurantes.json', 'data/bares.json', 'data/baladas.json', 'data/espacos_kids.json', 'data/eventos.json', 'data/mapa.json'];
    $todasAtracoes = [];
    foreach ($arquivos as $arquivo) {
        if (file_exists($arquivo) && filesize($arquivo) > 0) {
            $conteudo = file_get_contents($arquivo);
            $dados = json_decode($conteudo, true);
            if (is_array($dados)) {
                $todasAtracoes = array_merge($todasAtracoes, $dados);
            }
        }
    }
    return $todasAtracoes;
}

$todas_atracoes_json = json_encode(carregar_todas_atracoes());
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Favoritos - ColatinaMais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Damion&family=Montserrat:wght@400&family=Roboto:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <header class="top-header fixed-top text-white d-flex justify-content-between align-items-center p-3">
        <h1 class="logo mb-0">Meus Favoritos</h1>
        <a href="admin/login.php" class="text-white" title="Acesso do Administrador"><i class="bi bi-person-circle admin-login-icon header-icon"></i></a>
    </header>
    <main class="main-content container pt-3">
        <div id="favorites-container" class="row g-3"></div>
        <div id="empty-favorites" class="text-center text-muted" style="display: none; padding-top: 20vh;"><i class="bi bi-heart-fill" style="font-size: 5rem; color: #ccc;"></i><h4 class="mt-3">Nenhum favorito</h4><p>Você ainda não adicionou conteúdo.</p></div>
    </main>
    <nav class="bottom-nav fixed-bottom text-white d-flex justify-content-around align-items-center py-2 shadow-lg">
        <a href="index.php" class="bottom-nav-item" data-nav-action="home"><i class="bi bi-house-fill d-block mb-1"></i>Início</a>
        <a href="favorites.php" class="bottom-nav-item active" data-nav-action="favoritos"><i class="bi bi-heart-fill d-block mb-1"></i>Favoritos</a>
        <a href="list.php" class="bottom-nav-item" data-nav-action="categorias"><i class="bi bi-grid-fill d-block mb-1"></i>Categorias</a>
    </nav>
    <script> var todasAtracoes = <?php echo $todas_atracoes_json; ?>; </script>
    <script src="assets/js/main.js"></script> 
</body>
</html>