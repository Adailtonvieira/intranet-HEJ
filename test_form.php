<?php
// test_form.php - Para testar se o formulário está enviando dados
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Dados Recebidos:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h2>Headers:</h2>";
    echo "<pre>";
    foreach (getallheaders() as $name => $value) {
        echo "$name: $value\n";
    }
    echo "</pre>";
    
    echo "<p><strong>Status:</strong> ✅ Dados recebidos com sucesso!</p>";
} else {
    echo "<p>❌ Acesse esta página via POST</p>";
}
?>