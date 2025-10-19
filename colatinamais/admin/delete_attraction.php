<?php
header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Erro ao excluir atração.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $tipo = $_POST['tipo'] ?? null;

    if ($id && $tipo) {
        $jsonFile = '';
        switch ($tipo) {
            case 'restaurante': $jsonFile = '../data/restaurantes.json'; break;
            case 'bar': $jsonFile = '../data/bares.json'; break;
            case 'balada': $jsonFile = '../data/baladas.json'; break;
            // ✅ NOVOS CASOS ADICIONADOS
            case 'espaco_kids': $jsonFile = '../data/espacos_kids.json'; break;
            case 'evento': $jsonFile = '../data/eventos.json'; break;
            case 'mapa': $jsonFile = '../data/mapa.json'; break;
        }

        if (!empty($jsonFile) && file_exists($jsonFile)) {
            $jsonContent = file_get_contents($jsonFile);
            $data = json_decode($jsonContent, true);
            $imageToDelete = '';
            
            $updatedData = array_filter($data, function($item) use ($id, &$imageToDelete) {
                if ($item['id'] === $id) {
                    $imageToDelete = $item['imagem'];
                    return false; // Remove o item
                }
                return true; // Mantém o item
            });

            // Re-indexa o array para evitar problemas com JSON
            $updatedData = array_values($updatedData);

            if (file_put_contents($jsonFile, json_encode($updatedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                // Tenta excluir a imagem associada
                if (!empty($imageToDelete) && file_exists('../' . $imageToDelete)) {
                    unlink('../' . $imageToDelete);
                }
                $response['success'] = true;
                $response['message'] = 'Atração excluída com sucesso!';
            } else {
                $response['message'] = 'Falha ao salvar as alterações no arquivo.';
            }
        } else {
            $response['message'] = 'Arquivo de dados não encontrado para o tipo: ' . $tipo;
        }
    } else {
        $response['message'] = 'ID ou tipo da atração não fornecido.';
    }
}

echo json_encode($response);
?>