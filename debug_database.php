<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// debug_database.php
include 'db_config.php';

echo "<h2>🔍 Debug do Banco de Dados</h2>";

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        die("❌ Não foi possível conectar ao banco de dados");
    }

    echo "✅ Conectado ao banco 'agendamento_auditorio'<br><br>";
    
    // Ver tabelas existentes
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    echo "<h3>📋 Tabelas no banco:</h3>";
    foreach ($tables as $table) {
        echo "- " . $table['Tables_in_agendamento_auditorio'] . "<br>";
    }
    
    echo "<br><h3>📊 Estrutura da tabela 'agendamentos':</h3>";
    $stmt = $db->query("DESCRIBE agendamentos");
    $structure = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Default</th></tr>";
    foreach ($structure as $field) {
        echo "<tr>";
        echo "<td>" . $field['Field'] . "</td>";
        echo "<td>" . $field['Type'] . "</td>";
        echo "<td>" . $field['Null'] . "</td>";
        echo "<td>" . $field['Key'] . "</td>";
        echo "<td>" . $field['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><h3>📝 Agendamentos existentes:</h3>";
    $stmt = $db->query("SELECT * FROM agendamentos ORDER BY dataSolicitacao DESC");
    $agendamentos = $stmt->fetchAll();
    
    if (count($agendamentos) > 0) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Data</th><th>Horário</th><th>Status</th><th>Data Solicitação</th></tr>";
        foreach ($agendamentos as $ag) {
            echo "<tr>";
            echo "<td>" . substr($ag['id'], 0, 15) . "...</td>";
            echo "<td>" . $ag['nome'] . "</td>";
            echo "<td>" . $ag['data_evento'] . "</td>";
            echo "<td>" . $ag['horario'] . "</td>";
            echo "<td>" . $ag['status'] . "</td>";
            echo "<td>" . $ag['dataSolicitacao'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ Nenhum agendamento encontrado na tabela";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
}
?>