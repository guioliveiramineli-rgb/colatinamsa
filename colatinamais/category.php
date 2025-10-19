<?php
$tipo = $_GET['tipo'] ?? 'desconhecido';
$titulo_pagina = ucfirst(str_replace('_', ' ', $tipo));

function carregar_lista_completa($tipo) {
    $arquivo_json = '';
    // ✅ SWITCH ATUALIZADO
    switch ($tipo) {
        case 'restaurante': $arquivo_json = 'data/restaurantes.json'; break;
        case 'bar': $arquivo_json = 'data/bares.json'; break;
        case 'balada': $arquivo_json = 'data/baladas.json'; break;
        case 'espaco_kids': $arquivo_json = 'data/espacos_kids.json'; break;
        case 'evento': $arquivo_json = 'data/eventos.json'; break;
        case 'mapa': $arquivo_json = 'data/mapa.json'; break;
        default: return []; 
    }
    if (file_exists($arquivo_json) && filesize($arquivo_json) > 0) {
        $conteudo = file_get_contents($arquivo_json);
        $dados = json_decode($conteudo, true);
        return is_array($dados) ? $dados : []; 
    }
    return []; 
}
$lista_atracoes = carregar_lista_completa($tipo);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo_pagina); ?> - ColatinaMais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Damion&family=Montserrat:wght@400&family=Roboto:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <header class="top-header fixed-top text-white d-flex justify-content-between align-items-center p-3">
        <a href="list.php" class="text-white"><i class="bi bi-arrow-left header-icon"></i></a>
        <h1 class="logo mb-0"><?php echo htmlspecialchars($titulo_pagina); ?></h1>
        <a href="admin/login.php" class="text-white" title="Acesso do Administrador"><i class="bi bi-person-circle admin-login-icon header-icon"></i></a>
    </header>
    <main class="main-content container pt-3">
        <div class="row g-3">
            <?php if (empty($lista_atracoes)): ?>
                <div class="col-12 text-center text-muted" style="padding-top: 20vh;"><i class="bi bi-search" style="font-size: 4rem; color: #ccc;"></i><h4 class="mt-3">Nada encontrado</h4><p>Nenhum item cadastrado nesta categoria ainda.</p></div>
            <?php else: ?>
                <?php foreach ($lista_atracoes as $atracao): ?>
                    <div class="col-12">
                        <a href="details.php?id=<?php echo htmlspecialchars($atracao['id']); ?>" class="favorite-card">
                            <img src="<?php echo htmlspecialchars($atracao['imagem']); ?>" alt="<?php echo htmlspecialchars($atracao['nome']); ?>">
                            <div class="favorite-card-info">
                                <h6><?php echo htmlspecialchars($atracao['nome']); ?></h6>
                                <span class="badge bg-primary text-capitalize"><?php echo htmlspecialchars(str_replace('_', ' ', $atracao['tipo'])); ?></span>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>