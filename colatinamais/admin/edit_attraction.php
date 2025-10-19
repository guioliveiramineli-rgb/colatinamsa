<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
$atracao = null;

if ($id) {
    $tipo = explode('_', $id)[0];
    $arquivo_origem = '';

    // ✅ SWITCH ATUALIZADO PARA ENCONTRAR O ARQUIVO
    switch ($tipo) {
        case 'restaurante': $arquivo_origem = '../data/restaurantes.json'; break;
        case 'bar': $arquivo_origem = '../data/bares.json'; break;
        case 'balada': $arquivo_origem = '../data/baladas.json'; break;
        case 'espaco_kids': $arquivo_origem = '../data/espacos_kids.json'; break;
        case 'evento': $arquivo_origem = '../data/eventos.json'; break;
        case 'mapa': $arquivo_origem = '../data/mapa.json'; break;
        default: $arquivo_origem = ''; break;
    }

    if (!empty($arquivo_origem) && file_exists($arquivo_origem)) {
        $jsonContent = file_get_contents($arquivo_origem);
        $data = json_decode($jsonContent, true);
        if (is_array($data)) {
            foreach ($data as $item) {
                if ($item['id'] === $id) {
                    $atracao = $item;
                    break;
                }
            }
        }
    }
}

if (!$atracao) {
    die("Atração não encontrada.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Atração</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 700px;">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3><i class="bi bi-pencil-square me-2"></i>Editar Atração</h3>
            </div>
            <div class="card-body p-4">
                <form action="update_attraction.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($atracao['id']); ?>">
                    <input type="hidden" name="tipo_original" value="<?php echo htmlspecialchars($atracao['tipo']); ?>">
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Atração</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($atracao['nome']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?php echo htmlspecialchars($atracao['descricao']); ?></textarea>
                    </div>
                    <hr class="my-4">
                    <h6 class="text-muted mb-3">Informações Adicionais</h6>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($atracao['telefone'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($atracao['email'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="website" class="form-label">Website/Rede Social</label>
                        <input type="url" class="form-control" id="website" name="website" value="<?php echo htmlspecialchars($atracao['website'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="ticket_url" class="form-label">Link Ticket/Ingresso</label>
                        <input type="url" class="form-control" id="ticket_url" name="ticket_url" value="<?php echo htmlspecialchars($atracao['ticket_url'] ?? ''); ?>">
                    </div>
                    <hr class="my-4">
                    <div class="mb-3">
                        <label for="google_maps_url" class="form-label">URL do Google Maps</label>
                        <input type="url" class="form-control" id="google_maps_url" name="google_maps_url" value="<?php echo htmlspecialchars($atracao['google_maps_url']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Nova Imagem (Opcional)</label>
                        <input class="form-control" type="file" id="imagem" name="imagem" accept="image/*">
                        <small class="form-text text-muted">Envie uma nova imagem apenas se desejar substituir a atual.</small>
                    </div>
                    <div class="mb-3">
                        <p class="form-label">Imagem Atual:</p>
                        <img src="../<?php echo htmlspecialchars($atracao['imagem']); ?>" width="150" class="img-thumbnail">
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>