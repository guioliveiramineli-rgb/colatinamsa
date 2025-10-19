<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}
if (isset($_SESSION['session_expire_time']) && time() > $_SESSION['session_expire_time']) {
    session_unset(); session_destroy();
    header('location: login.php?reason=session_expired');
    exit;
}
$timeLeft = isset($_SESSION['session_expire_time']) ? $_SESSION['session_expire_time'] - time() : 0;

function carregar_todas_atracoes_admin() {
    $arquivos = ['../data/restaurantes.json', '../data/bares.json', '../data/baladas.json', '../data/espacos_kids.json', '../data/eventos.json', '../data/mapa.json'];
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
    if (!empty($todasAtracoes)) {
        usort($todasAtracoes, fn($a, $b) => strcmp($a['nome'], $b['nome']));
    }
    return $todasAtracoes;
}
$atracoes = carregar_todas_atracoes_admin();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar Atrações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .card-header { background-color: #fff; border-bottom: 1px solid #dee2e6; font-weight: 600; }
        .form-label { font-weight: 500; }
        .table img { border: 1px solid #eee; }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 900px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0 text-secondary"><i class="bi bi-gear-fill me-2"></i>Painel de Controle</h3>
            <div>
                <span class="badge bg-dark text-white me-2">Sessão expira em: <span id="session-timer" data-timeleft="<?php echo $timeLeft; ?>">...</span></span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right me-1"></i> Sair</a>
            </div>
        </div>
        <div class="card mb-5 border-0">
            <div class="card-header py-3"><h5 class="mb-0"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Cadastrar Nova Atração</h5></div>
            <div class="card-body p-4">
                <form id="cadastro-form" enctype="multipart/form-data">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7"><label for="nome" class="form-label">Nome da Atração</label><input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex: Restaurante Sabor da Cidade"></div>
                        <div class="col-md-5">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="" selected disabled>Selecione a categoria...</option>
                                <option value="restaurante">Restaurante</option>
                                <option value="bar">Bar</option>
                                <option value="balada">Balada</option>
                                <option value="espaco_kids">Espaços Kids</option>
                                <option value="evento">Eventos</option>
                                <option value="mapa">Mapa (Pontos Turísticos)</option> 
                            </select>
                        </div>
                    </div>
                    <div class="mb-3"><label for="descricao" class="form-label">Descrição Curta</label><textarea class="form-control" id="descricao" name="descricao" rows="3" required placeholder="Descreva brevemente o local..."></textarea></div>
                    <hr class="my-4">
                    <h6 class="text-muted mb-3">Informações Adicionais (Opcional)</h6>
                    <div class="row g-3 mb-3">
                         <div class="col-md-6"><label for="telefone" class="form-label"><i class="bi bi-telephone me-1"></i>Telefone</label><input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(27) XXXXX-XXXX"></div>
                         <div class="col-md-6"><label for="email" class="form-label"><i class="bi bi-envelope me-1"></i>E-mail</label><input type="email" class="form-control" id="email" name="email" placeholder="contato@email.com"></div>
                    </div>
                     <div class="mb-3"><label for="website" class="form-label"><i class="bi bi-link-45deg me-1"></i>Website / Rede Social</label><input type="url" class="form-control" id="website" name="website" placeholder="https://www.seusite.com"></div>
                     <div class="mb-4"><label for="ticket_url" class="form-label"><i class="bi bi-ticket me-1"></i>Link Ticket/Ingresso</label><input type="url" class="form-control" id="ticket_url" name="ticket_url" placeholder="https://www.siteingresso.com"></div>
                    <hr class="my-4">
                     <div class="mb-3"><label for="google_maps_url" class="form-label"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>URL do Google Maps <span class="text-danger">*</span></label><input type="url" class="form-control" id="google_maps_url" name="google_maps_url" placeholder="Cole o link completo aqui" required></div>
                    <div class="mb-4"><label for="imagem" class="form-label"><i class="bi bi-image me-1"></i>Foto Principal <span class="text-danger">*</span></label><input class="form-control" type="file" id="imagem" name="imagem" accept="image/jpeg, image/png, image/webp" required><small class="form-text text-muted">Envie uma imagem chamativa (JPG, PNG, WebP).</small></div>
                    <button type="submit" class="btn btn-primary btn-lg w-100"><i class="bi bi-check-circle me-1"></i> Salvar Nova Atração</button>
                </form>
                <div id="status-message" class="mt-3"></div>
            </div>
        </div>
        <div class="card border-0">
            <div class="card-header py-3 d-flex justify-content-between align-items-center"><h5 class="mb-0"><i class="bi bi-list-ul me-2 text-secondary"></i>Gerenciar Atrações</h5><a href="dashboard.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i> Atualizar</a></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th class="ps-3">Imagem</th><th>Nome</th><th>Tipo</th><th class="text-end pe-3">Ações</th></tr></thead>
                        <tbody id="lista-atracoes">
                            <?php if (empty($atracoes)): ?>
                                <tr><td colspan="4" class="text-center text-muted py-5">Nenhuma atração cadastrada.</td></tr>
                            <?php else: ?>
                                <?php foreach ($atracoes as $atracao): ?>
                                    <tr id="atracao-<?php echo htmlspecialchars($atracao['id']); ?>">
                                        <td class="ps-3"><img src="../<?php echo htmlspecialchars($atracao['imagem']); ?>" width="60" class="rounded" style="object-fit: cover; height: 40px; aspect-ratio: 16/10;"></td>
                                        <td><?php echo htmlspecialchars($atracao['nome']); ?></td>
                                        <td><span class="badge bg-secondary bg-opacity-25 text-dark fw-normal text-capitalize"><?php echo htmlspecialchars(str_replace('_', ' ', $atracao['tipo'])); ?></span></td>
                                        <td class="text-end pe-3">
                                            <a href="edit_attraction.php?id=<?php echo htmlspecialchars($atracao['id']); ?>" class="btn btn-outline-warning btn-sm" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="<?php echo htmlspecialchars($atracao['id']); ?>" data-tipo="<?php echo htmlspecialchars($atracao['tipo']); ?>" title="Excluir"><i class="bi bi-trash-fill"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/dashboard.js"></script> 
</body>
</html>