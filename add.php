<?php
//Criar usuário administrador
$host = 'localhost';
$dbname = 'agendamento_auditorio';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    //verificar e corrigir o AUTO_INCREMENT se necessário
    $pdo->exec("ALTER TABLE users AUTO_INCREMENT = 1");
    
    // Dados do admin
    $admin_user = 'acessoti';
    $admin_password = 'ti123';
    $password_hash = password_hash($admin_password, PASSWORD_DEFAULT);
    
    // Inserir usuário
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->execute([$admin_user, $password_hash]);
    
    echo "✅ Usuário admin criado com sucesso!<br>";
    echo "Usuário: <strong>$admin_user</strong><br>";
    echo "Senha: <strong>$admin_password</strong><br>";
    echo "<a href='login.php'>Fazer login</a>";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
    
    // Tentar solução alternativa
    try {
        $stmt = $pdo->prepare("INSERT INTO users (id, username, password_hash) VALUES (1, ?, ?)");
        $stmt->execute([$admin_user, $password_hash]);
        echo "✅ Usuário criado com ID fixo!";
    } catch (Exception $e2) {
        echo "<br>❌ Erro alternativo: " . $e2->getMessage();
    }
}
?>