<?php
function find_atracao_by_id($id) {
    if (empty($id)) return null;
    $partes = explode('_', $id);
    // Se começa com "espaco", pega "espaco_kids"
    if ($partes[0] === 'espaco' && isset($partes[1]) && $partes[1] === 'kids') {
        $tipo = 'espaco_kids';
    } else {
        $tipo = $partes[0];
    }
    $arquivo_json = '';
    // ✅ SWITCH ATUALIZADO COM TODAS AS CATEGORIAS
    switch ($tipo) {
        case 'restaurante': $arquivo_json = 'data/restaurantes.json'; break;
        case 'bar': $arquivo_json = 'data/bares.json'; break;
        case 'balada': $arquivo_json = 'data/baladas.json'; break;
        case 'espaco_kids': $arquivo_json = 'data/espacos_kids.json'; break;
        case 'evento': $arquivo_json = 'data/eventos.json'; break;
        case 'mapa': $arquivo_json = 'data/mapa.json'; break;
        default: return null;
    }
    if (file_exists($arquivo_json)) {
        $dados = json_decode(file_get_contents($arquivo_json), true);
        if (is_array($dados)) {
            foreach ($dados as $item) {
                if (isset($item['id']) && $item['id'] === $id) return $item;
            }
        }
    }
    return null;
}
$id_atracao = $_GET['id'] ?? null;
$atracao = find_atracao_by_id($id_atracao);
$titulo_pagina = $atracao['nome'] ?? "Erro";
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
        <a href="javascript:history.back()" class="text-white"><i class="bi bi-arrow-left header-icon"></i></a>
        <h5 class="logo mb-0 text-truncate px-3"><?php echo htmlspecialchars($titulo_pagina); ?></h5>
        <a href="admin/login.php" class="text-white"><i class="bi bi-person-circle admin-login-icon header-icon"></i></a>
    </header>
    <main class="main-content">
        <?php if (!$atracao): ?>
            <div class='container mt-5 pt-5'><div class='alert alert-danger'>Atração não encontrada ou dados inválidos. Verifique se o ID na URL está correto.</div><a href='index.php' class='btn btn-primary'>Voltar para o Início</a></div>
        <?php else:
            $img_path = $atracao['imagem'] ?? '';
            // Check if image file exists, otherwise use placeholder
            if (!empty($img_path) && file_exists($img_path)) {
                $img = htmlspecialchars($img_path);
            } else {
                // Use placeholder if image doesn't exist
                $img = 'https://placehold.co/800x400/1E3170/C2A146?text=' . urlencode($atracao['nome'] ?? 'Imagem');
            }
            
            $nome = htmlspecialchars($atracao['nome'] ?? '');
            $tipo_atracao = htmlspecialchars(str_replace('_', ' ', $atracao['tipo'] ?? '')); $desc = htmlspecialchars($atracao['descricao'] ?? '');
            $maps_url = htmlspecialchars($atracao['google_maps_url'] ?? '#'); $id_atual = htmlspecialchars($atracao['id']);
            $telefone = htmlspecialchars($atracao['telefone'] ?? ''); $email = htmlspecialchars($atracao['email'] ?? '');
            $website = htmlspecialchars($atracao['website'] ?? ''); $ticket_url = htmlspecialchars($atracao['ticket_url'] ?? '');
            $toolbar_info = '';
            if ($telefone) $toolbar_info .= "<a href='tel:{$telefone}' class='btn me-2 flex-fill info-button' title='Ligar'><i class='bi bi-telephone-fill d-block mb-1'></i><small>Telefone</small></a>";
            if ($email) $toolbar_info .= "<a href='mailto:{$email}' class='btn me-2 flex-fill info-button' title='Enviar E-mail'><i class='bi bi-envelope-fill d-block mb-1'></i><small>Email</small></a>";
            if ($website) $toolbar_info .= "<a href='{$website}' target='_blank' class='btn me-2 flex-fill info-button' title='Ver Website'><i class='bi bi-link-45deg d-block mb-1'></i><small>Site</small></a>";
            $toolbar_info .= "<button class='btn flex-fill info-button' id='share-button' title='Compartilhar'><i class='bi bi-share-fill d-block mb-1'></i><small>Compartilhar</small></button>";
        ?>
            <div class='details-image-container'><img src='<?php echo $img; ?>' class='details-image' alt='<?php echo $nome; ?>' onerror="this.src='https://placehold.co/800x400/1E3170/C2A146?text=<?php echo urlencode($nome); ?>'"><div class='details-image-overlay'></div></div>
            <div class='container details-body'>
                <h1 class='details-title'><?php echo $nome; ?></h1>
                <span class='badge bg-primary mb-3 text-capitalize'><?php echo $tipo_atracao; ?></span>
                <p class='details-description'><?php echo nl2br($desc); ?></p>
                <div class='d-flex justify-content-around mb-4 info-toolbar'><?php echo $toolbar_info; ?></div>
                <a href='<?php echo $maps_url; ?>' target='_blank' class='btn btn-success w-100 py-2 mb-3'><i class='bi bi-geo-alt-fill'></i> Ver Rota / Google Maps</a> 
                <?php if ($ticket_url) echo "<a href='{$ticket_url}' target='_blank' class='btn btn-warning w-100 py-2 mb-3'><i class='bi bi-ticket-fill'></i> Comprar Ticket / Ingresso</a>"; ?>
                <button class='btn btn-outline-danger w-100 favorito-btn-details' data-id='<?php echo $id_atual; ?>'><i class='bi bi-heart'></i> Adicionar aos Favoritos</button>
            </div>
        <?php endif; ?>
    </main>
    <script src="assets/js/main.js"></script> 
</body>
</html>
