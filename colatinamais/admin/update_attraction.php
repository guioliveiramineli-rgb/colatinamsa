<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $tipo = $_POST['tipo_original']; // Usa o tipo original para achar o arquivo certo

    $jsonFile = '';
    // ✅ SWITCH ATUALIZADO
    switch ($tipo) {
        case 'restaurante': $jsonFile = '../data/restaurantes.json'; break;
        case 'bar': $jsonFile = '../data/bares.json'; break;
        case 'balada': $jsonFile = '../data/baladas.json'; break;
        case 'espaco_kids': $jsonFile = '../data/espacos_kids.json'; break;
        case 'evento': $jsonFile = '../data/eventos.json'; break;
        case 'mapa': $jsonFile = '../data/mapa.json'; break;
    }

    if (!empty($jsonFile) && file_exists($jsonFile)) {
        $jsonContent = file_get_contents($jsonFile);
        $data = json_decode($jsonContent, true);
        $key = array_search($id, array_column($data, 'id'));

        if ($key !== false) {
            // Atualiza os dados de texto
            $data[$key]['nome'] = $_POST['nome'];
            $data[$key]['descricao'] = $_POST['descricao'];
            $data[$key]['google_maps_url'] = $_POST['google_maps_url'];
            $data[$key]['telefone'] = isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : '';
            $data[$key]['email'] = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
            $data[$key]['website'] = isset($_POST['website']) ? filter_var($_POST['website'], FILTER_SANITIZE_URL) : '';
            $data[$key]['ticket_url'] = isset($_POST['ticket_url']) ? filter_var($_POST['ticket_url'], FILTER_SANITIZE_URL) : '';
            
            // Lógica de atualização da imagem
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $oldImagePath = '../' . $data[$key]['imagem'];
                
                $uploadDir = '../uploads/';
                $imageFileType = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
                $newFileName = uniqid() . '-' . basename($_FILES['imagem']['name']);
                $targetFile = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFile)) {
                    // Deleta a imagem antiga se o upload da nova for bem-sucedido
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                    $data[$key]['imagem'] = 'uploads/' . $newFileName;
                }
            }

            file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
}

header('location: dashboard.php');
exit;
?>