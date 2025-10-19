<?php
session_start();
header('Content-Type: application/json');

// Diretório e nome do arquivo central de favoritos
define('FAVORITES_FILE', '../data/all_favorites.json');

// 1. Verifica se o usuário está logado
if (!isset($_SESSION["loggedin_app"]) || $_SESSION["loggedin_app"] !== true) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Usuário não autenticado. Por favor, faça login."]);
    exit;
}

$user_id = $_SESSION["id_app"]; // ID único do usuário logado
$user_id_str = (string)$user_id; // Garante que a chave no JSON é uma string (prática comum)
$action = $_SERVER['REQUEST_METHOD'];

// Função auxiliar para ler e decodificar o arquivo JSON
function getAllFavorites() {
    if (!file_exists(FAVORITES_FILE)) {
        // Se o arquivo não existir, retorna a estrutura base
        return [];
    }
    $json = file_get_contents(FAVORITES_FILE);
    // Decodifica o JSON, retorna um array vazio se o arquivo estiver vazio ou corrompido
    return json_decode($json, true) ?: []; 
}

// Função auxiliar para salvar o array completo de volta no arquivo JSON
function saveAllFavorites(array $allFavorites) {
    $json = json_encode($allFavorites, JSON_PRETTY_PRINT);
    
    // Tenta salvar no arquivo
    if (file_put_contents(FAVORITES_FILE, $json) === false) {
        return false;
    }
    return true;
}

switch ($action) {
    case 'GET':
        // Ação para CARREGAR os favoritos do usuário
        $allFavorites = getAllFavorites();
        
        // Busca a lista de favoritos usando a chave do ID do usuário
        $userFavorites = $allFavorites[$user_id_str] ?? [];
        
        echo json_encode(["success" => true, "favorites" => $userFavorites]);
        break;

    case 'POST':
        // Ação para SALVAR os favoritos do usuário
        $data = json_decode(file_get_contents("php://input"), true);
        $new_favorites = $data['favorites'] ?? [];
        
        // 1. Lê todos os favoritos existentes
        $allFavorites = getAllFavorites();

        // 2. Atualiza APENAS a entrada do usuário logado
        $allFavorites[$user_id_str] = $new_favorites;
        
        // 3. Salva o array completo de volta no arquivo
        if (saveAllFavorites($allFavorites)) {
            echo json_encode(["success" => true, "message" => "Favoritos salvos com sucesso no arquivo JSON central."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Erro ao salvar o arquivo JSON de favoritos. Verifique permissões."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Método não permitido."]);
        break;
}
?>