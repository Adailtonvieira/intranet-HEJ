<?php
// login.php - Versão corrigida
session_start();

// Configuração direta do banco (sem config.php)
$host = 'localhost';
$dbname = 'agendamento_auditorio';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Verificar se já está logado
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: adm.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_username = trim($_POST['username']);
    $input_password = $_POST['password'];
    
    if (!empty($input_username) && !empty($input_password)) {
        try {
            // Buscar usuário no banco
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$input_username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Verificar senha
                if (password_verify($input_password, $user['password_hash'])) {
                    // Login bem-sucedido
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_id'] = $user['id'];
                    
                    echo "<script>alert('Login bem-sucedido!'); window.location.href = 'adm.php';</script>";
                    exit;
                } else {
                    $error = 'Senha incorreta!';
                }
            } else {
                $error = 'Usuário não encontrado!';
            }
        } catch (Exception $e) {
            $error = 'Erro no sistema: ' . $e->getMessage();
        }
    } else {
        $error = 'Por favor, preencha todos os campos!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HEJ</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: #00766f; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
        .login-container { 
            background: white; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.2); 
            width: 100%; 
            max-width: 400px; 
        }
        .logo { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .title { 
            text-align: center; 
            color: #00766f; 
            margin-bottom: 30px; 
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        .form-group input { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 16px; 
        }
        .btn-login { 
            width: 100%; 
            padding: 12px; 
            background: #00766f; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px; 
            cursor: pointer; 
        }
        .btn-login:hover { 
            background: #005a54; 
        }
        .error { 
            background: #ffebee; 
            color: #c62828; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
            text-align: center; 
        }
        .info { 
            background: #e3f2fd; 
            color: #1565c0; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
            text-align: center; 
            font-size: 14px; 
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="title">
            <h1>Gerênciar Auditorio</h1>
            <p>Hospital Estadual de Jataí</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="username" placeholder="Usuário" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Senha" required>
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="username"]').focus();
        });
    </script>
</body>
</html>