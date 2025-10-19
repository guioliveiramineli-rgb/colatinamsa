<?php
// Caminho do arquivo de usuários
define('USERS_FILE', '../data/users.json');

// Função para ler o arquivo de usuários
function getUsers() {
    // Certifica-se de que o diretório 'data' existe
    if (!is_dir(dirname(USERS_FILE))) {
        mkdir(dirname(USERS_FILE), 0755, true);
    }
    
    // Cria o arquivo se ele não existir
    if (!file_exists(USERS_FILE)) {
        file_put_contents(USERS_FILE, '[]');
        return [];
    }
    
    $json = file_get_contents(USERS_FILE);
    // Retorna o array decodificado ou um array vazio em caso de erro
    return json_decode($json, true) ?: [];
}

// Função para salvar o array de usuários no arquivo
function saveUsers(array $users) {
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    // Tenta garantir que o arquivo é gravável
    if (!is_writable(dirname(USERS_FILE))) {
        // Log de erro de permissão ou tratamento mais robusto aqui
        return false; 
    }
    
    // Salva o conteúdo no arquivo
    return file_put_contents(USERS_FILE, $json, LOCK_EX) !== false;
}

// Função para encontrar um usuário pelo nome
function findUserByUsername($username) {
    $users = getUsers();
    foreach ($users as $user) {
        if (isset($user['username']) && $user['username'] === $username) {
            return $user;
        }
    }
    return null;
}

// Função para gerar um novo ID
function getNextUserId() {
    $users = getUsers();
    $max_id = 0;
    foreach ($users as $user) {
        if (isset($user['id']) && $user['id'] > $max_id) {
            $max_id = $user['id'];
        }
    }
    return $max_id + 1;
}
?>