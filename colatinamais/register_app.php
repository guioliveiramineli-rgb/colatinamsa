<?php
// Inclui o arquivo de funções
require_once "php/auth_json.php";

// Variáveis de estado
$username = $password = "";
$username_err = $password_err = "";

// Processa o formulário quando submetido
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // 1. Validação do nome de usuário
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor, insira um nome de usuário.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "O nome de usuário pode conter apenas letras, números e sublinhados.";
    } elseif (findUserByUsername(trim($_POST["username"]))) {
        $username_err = "Este nome de usuário já está em uso.";
    } else {
        $username = trim($_POST["username"]);
    }

    // 2. Validação da senha
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor, insira uma senha.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }

    // 3. Insere o usuário no JSON se não houver erros
    if(empty($username_err) && empty($password_err)){
        $users = getUsers();
        
        // Hash da senha (MUITO IMPORTANTE para segurança!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $new_user = [
            'id' => getNextUserId(),
            'username' => $username,
            'password' => $hashed_password,
            'created_at' => date("Y-m-d H:i:s")
        ];
        
        $users[] = $new_user;

        if(saveUsers($users)){
            // Redireciona para a página de login
            header("location: login_app.php");
            exit;
        } else {
            echo "Ops! Algo deu errado ao salvar o arquivo. Verifique as permissões do diretório 'data'.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - Colatina Mais</title>
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Estilos básicos para a tela de login/cadastro */
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
            background-color: #dc3545; /* Cor vermelha para combinar com o app */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #c82333;
        }
        /* Ajuste para o seu design, se necessário */
    </style>
</head>
<body>
    <div class="auth-container">
        <h2><i class="bi bi-person-circle"></i> Criar Conta</h2>
        <p>Cadastre-se para salvar seus favoritos em qualquer dispositivo.</p>
        
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
                <input type="submit" class="btn-primary" value="Cadastrar">
            </div>
            
            <p>Já tem uma conta? <a href="login_app.php">Faça login aqui</a>.</p>
            <p><a href="index.php">Voltar para o aplicativo</a></p>
        </form>
    </div>    
</body>
</html>