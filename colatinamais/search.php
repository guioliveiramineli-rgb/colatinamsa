<?php
header('Content-Type: application/json');

// 1. Pega o termo que o utilizador digitou
$term = strtolower(trim($_GET['term'] ?? ''));

if (empty($term)) {
    echo json_encode([]);
    exit();
}

$resultados = [];
$data_directory = 'data/';
$categorias = ['restaurantes', 'bares', 'baladas', 'espacos_kids', 'eventos', 'mapa'];

// 2. Procura em todos os ficheiros de categorias
foreach ($categorias as $categoria) {
    $file_path = $data_directory . $categoria . '.json';
    if (file_exists($file_path)) {
        $items = json_decode(file_get_contents($file_path), true);
        if (is_array($items)) {
            foreach ($items as $item) {
                // 3. Compara o nome do item com o termo da busca (ignorando maiúsculas/minúsculas)
                if (isset($item['nome']) && stripos(strtolower($item['nome']), $term) !== false) {
                    $item['tipo'] = $categoria; // Adiciona a categoria ao resultado
                    $resultados[] = $item;
                }
            }
        }
    }
}

// 4. Devolve os resultados encontrados em formato JSON
echo json_encode($resultados);
?>