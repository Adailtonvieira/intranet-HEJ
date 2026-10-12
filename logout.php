<?php
// logout.php
session_start();

// Registrar log de logout
if (isset($_SESSION['username'])) {
    require 'config.php';
    $sql_log = "INSERT INTO logs (usuario, acao, detalhes, ip) VALUES (?, ?, ?, ?)";
    $stmt_log = $pdo->prepare($sql_log);
    $stmt_log->execute([
        $_SESSION['username'],
        'Logout do sistema',
        'Usuário saiu do painel administrativo',
        $_SERVER['REMOTE_ADDR'] ?? 'Desconhecido'
    ]);
}

// Destruir sessão
session_destroy();

// Redirecionar para login
header('Location: login.php');
exit;
?>