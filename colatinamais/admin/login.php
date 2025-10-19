<?php
// Inicia a sessão
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('location: dashboard.php');
    exit;
}

// Suas credenciais e lógica de verificação continuam aqui
$correct_user = 'lucas';
$correct_pass = 'colatina+';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $correct_user && $password === $correct_pass) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        
        $timeout = 600; // 10 minutos
        $_SESSION['session_expire_time'] = time() + $timeout; 
        
        header('location: dashboard.php');
        exit;
    } else {
        $error_message = 'Usuário ou senha inválidos!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ColatinaMais Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/style-login.css">
    
    <style>
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <h2 class="text-center mb-4">COLATINA <span class="text-warning">+</span></h2>
            <h5 class="text-center text-muted mb-4">Acesso ao Painel</h5>

            <form action="login.php" method="post" novalidate>
                
                <?php if(isset($_GET['reason']) && $_GET['reason'] == 'session_expired'): ?>
                    <div class="alert alert-warning">Sua sessão expirou por inatividade. Faça o login novamente.</div>
                <?php endif; ?>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Usuário" required>
                    <label for="username">Usuário</label>
                </div>

                <div class="form-floating mb-4 password-container">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required>
                    <label for="password">Senha</label>
                    <i class="bi bi-eye-fill toggle-password" id="togglePassword"></i>
                </div>
                <?php if(!empty($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <button type="submit" class="btn btn-dark w-100 py-2">Entrar</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            togglePassword.addEventListener('click', function () {
                // Verifica o tipo do campo de senha
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Troca o ícone do olho
                this.classList.toggle('bi-eye-fill');
                this.classList.toggle('bi-eye-slash-fill');
            });
        });
    </script>

</body>
</html>