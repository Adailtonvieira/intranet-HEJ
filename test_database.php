<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// test_database.php
header('Content-Type: text/html; charset=utf-8');

include 'db_config.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "<h2>✅ Conexão com o banco 'agendamento_auditorio' bem-sucedida!</h2>";
    
    // Verificar se a tabela agendamentos existe
    $stmt = $db->query("SHOW TABLES LIKE 'agendamentos'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ Tabela 'agendamentos' existe</p>";
        
        // Contar registros
        $stmt = $db->query("SELECT COUNT(*) as total FROM agendamentos");
        $total = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>📊 Total de agendamentos: " . $total['total'] . "</p>";
        
        // Mostrar últimos 5 agendamentos
        $stmt = $db->query("SELECT * FROM agendamentos ORDER BY dataSolicitacao DESC LIMIT 5");
        $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Últimos agendamentos:</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Data</th><th>Horário</th><th>Status</th></tr>";
        foreach ($agendamentos as $ag) {
            echo "<tr>";
            echo "<td>" . substr($ag['id'], 0, 10) . "...</td>";
            echo "<td>" . $ag['nome'] . "</td>";
            echo "<td>" . $ag['data_evento'] . "</td>";
            echo "<td>" . $ag['horario'] . "</td>";
            echo "<td>" . $ag['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ Tabela 'agendamentos' NÃO existe</p>";
    }
    
} catch (PDOException $e) {
    echo "<h2>❌ Erro na conexão: " . $e->getMessage() . "</h2>";
}
?>