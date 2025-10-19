<?php
header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Ocorreu um erro desconhecido.'];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método de requisição inválido.');
    }
    if (empty($_POST['nome']) || empty($_POST['tipo']) || empty($_POST['descricao']) || empty($_POST['google_maps_url'])) {
        throw new Exception('Todos os campos obrigatórios devem ser preenchidos.');
    }
    if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('O upload da imagem é obrigatório.');
    }

    $tipo = $_POST['tipo'];
    
    // ✅ SWITCH FINAL E CORRIGIDO
    $jsonFile = '';
    switch ($tipo) {
        case 'restaurante': $jsonFile = '../data/restaurantes.json'; break;
        case 'bar': $jsonFile = '../data/bares.json'; break;
        case 'balada': $jsonFile = '../data/baladas.json'; break;
        case 'espaco_kids': $jsonFile = '../data/espacos_kids.json'; break;
        case 'evento': $jsonFile = '../data/eventos.json'; break;
        case 'mapa': $jsonFile = '../data/mapa.json'; break;
        default: throw new Exception('Tipo de atração inválido.');
    }

    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
    $imageFileType = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($imageFileType, $allowedTypes)) { throw new Exception('Apenas imagens JPG, JPEG, PNG, GIF e WEBP são permitidas.'); }
    $newFileName = uniqid() . '-' . basename($_FILES['imagem']['name']);
    $targetFile = $uploadDir . $newFileName;
    if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFile)) { throw new Exception('Falha ao mover o arquivo de imagem.'); }
    $imagePath = 'uploads/' . $newFileName;

    $newAttraction = [
        'id' => $tipo . '_' . time(), 
        'nome' => htmlspecialchars($_POST['nome']), 'tipo' => $tipo,
        'descricao' => htmlspecialchars($_POST['descricao']),
        'google_maps_url' => filter_var($_POST['google_maps_url'], FILTER_SANITIZE_URL), 'imagem' => $imagePath,
        'telefone' => isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : '',
        'email' => isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '',
        'website' => isset($_POST['website']) ? filter_var($_POST['website'], FILTER_SANITIZE_URL) : '',
        'ticket_url' => isset($_POST['ticket_url']) ? filter_var($_POST['ticket_url'], FILTER_SANITIZE_URL) : '' 
    ];

    $currentData = [];
    if (file_exists($jsonFile)) {
        $jsonContent = file_get_contents($jsonFile);
        $currentData = json_decode($jsonContent, true);
        if (!is_array($currentData)) { $currentData = []; }
    }
    $currentData[] = $newAttraction;
    if (file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        $response['success'] = true; $response['message'] = 'Atração cadastrada com sucesso!';
        $response['atracao'] = $newAttraction;
    } else { throw new Exception('Falha ao salvar os dados no arquivo JSON.'); }
} catch (Exception $e) { $response['message'] = $e->getMessage(); }
echo json_encode($response);
?>