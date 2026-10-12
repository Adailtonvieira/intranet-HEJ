<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// test_email_diagnostico.php
header('Content-Type: text/html; charset=utf-8');
echo "<h2> Diagnóstico Completo do E-mail</h2>";


echo "<h3>1. Configurações do PHP:</h3>";
echo "<p>SMTP: <strong>" . ini_get('SMTP') . "</strong></p>";
echo "<p>smtp_port: <strong>" . ini_get('smtp_port') . "</strong></p>";
echo "<p>sendmail_from: <strong>" . ini_get('sendmail_from') . "</strong></p>";
echo "<p>sendmail_path: <strong>" . ini_get('sendmail_path') . "</strong></p>";

// 2. Teste SIMPLES
echo "<h3>2. Teste de Envio de E-mail:</h3>";

$to = "adailtonvieira0809@gmail.com"; 
$subject = "Teste de Diagnostico - " . date('H:i:s');
$message = "Mensagem de teste simples do PHP.";
$headers = "From: adailtonvieira0809@gmail.com\r\n";

// Limpar erros anteriores
error_clear_last();

$enviado = mail($to, $subject, $message, $headers);

if ($enviado) {
    echo "<p style='color: green; font-size: 18px;'>✅ E-mail ENVIADO com sucesso!</p>";
    echo "<p>Verifique sua caixa de entrada: <strong>adailtonvieira0809@gmail.com</strong></p>";
} else {
    echo "<p style='color: red; font-size: 18px;'>❌ FALHA no envio do e-mail</p>";
    
    $error = error_get_last();
    echo "<p><strong>Erro detalhado:</strong> " . ($error['message'] ?? 'Nenhum erro específico') . "</p>";
}

// 3. Verificar se o sendmail está acessível
echo "<h3>3. Verificação do Sendmail:</h3>";
$sendmail_path = ini_get('sendmail_path');
if (file_exists($sendmail_path)) {
    echo "<p>✅ Sendmail encontrado: $sendmail_path</p>";
} else {
    echo "<p style='color: red;'>❌ Sendmail NÃO encontrado: $sendmail_path</p>";
}

// 4. Teste com headers COMPLETOS
echo "<h3>4. Teste com Headers Completos:</h3>";
$headers_completos = "From: adailtonvieira0809@gmail.com\r\n" .
                    "Reply-To: adailtonvieira0809@gmail.com\r\n" .
                    "Content-Type: text/plain; charset=UTF-8\r\n" .
                    "X-Priority: 1\r\n" .
                    "X-Mailer: PHP/" . phpversion();

$enviado2 = mail($to, "Teste Headers Completos", "Teste com headers expandidos", $headers_completos);

echo "<p>Envio com headers completos: " . ($enviado2 ? "✅ SUCESSO" : "❌ FALHA") . "</p>";

echo "<hr><h3>🎯 Próximos Passos:</h3>";
if ($enviado) {
    echo "<p>O problema está no <strong>save_booking.php</strong> - vou corrigir!</p>";
} else {
    echo "<p>O problema está na <strong>configuração do XAMPP/Gmail</strong></p>";
}
?>
