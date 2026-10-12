<?php
header('Content-Type: application/json; charset=utf-8');

// Configurações do banco de dados
$host = 'localhost';
$dbname = 'agendamento_auditorio';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscar TODOS os agendamentos ordenados pelos mais recentes primeiro
    $sql = "SELECT * FROM agendamentos 
            ORDER BY dataSolicitacao DESC, data_criacao DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'agendamentos' => $agendamentos
    ]);
    
} catch (Exception $e) {
    error_log("Erro ao carregar agendamentos: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao carregar agendamentos',
        'agendamentos' => []
    ]);
}
?>