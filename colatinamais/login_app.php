<?php
// Inicia a sessão para rastrear o usuário
session_start();

// Verifica se o usuário já está logado no app, se sim, redireciona para a página inicial
if(isset($_SESSION["loggedin_app"]) && $_SESSION["loggedin_app"] === true){
    header("location: index.php");
    exit;
}

// Inclui o arquivo de funções
require_once "php/auth_json.php";

// Variáveis de estado
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processa o formulário quando submetido
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // 1. Validação básica de campos
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor, insira o nome de usuário.";
    } else{
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor, insira sua senha.";
    } else{
        $password = trim($_POST["password"]);
    }

    // 2. Tenta encontrar e autenticar o usuário
    if(empty($username_err) && empty($password_err)){
        $user = findUserByUsername($username);

        if($user){
            // Usuário encontrado, verifica a senha (usando a senha hashada do JSON)
            if(password_verify($password, $user['password'])){
                
                // Senha correta, inicia a sessão
                
                // Armazena dados nas variáveis de sessão
                $_SESSION["loggedin_app"] = true; // Flag de login
                $_SESSION["id_app"] = $user['id']; // ID Único do usuário
                $_SESSION["username_app"] = $user['username']; // Nome de usuário
                
                // Redireciona para a página inicial (index.php)
                header("location: index.php");
                exit;
            } else{
                // Senha inválida
                $login_err = "Nome de usuário ou senha inválidos.";
            }
        } else {
            // Nome de usuário não existe
            $login_err = "Nome de usuário ou senha inválidos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Colatina Mais</title>
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Reutiliza os estilos do cadastro */
        .auth-container {
            padding: 20px;
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .help-block {
            color: #dc3545;
            font-size: 0.85em;
            display: block;
            margin-top: 5px;
        }
        .btn-primary {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #dc3545; 
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #c82333;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid transparent;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2><i class="bi bi-box-arrow-in-right"></i> Acessar Conta</h2>
        <p>Faça login para carregar e salvar seus favoritos.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert-danger">' . htmlspecialchars($login_err) . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            
            <div class="form-group">
                <label for="username">Nome de Usuário</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn-primary" value="Entrar">
            </div>
            
            <p>Ainda não tem uma conta? <a href="register_app.php">Cadastre-se aqui</a>.</p>
            <p><a href="index.php">Voltar para o aplicativo</a></p>
        </form>
    </div>
</body>
</html>