<?php
require_once 'send_status_email.php';

// Teste direto
$teste = enviarEmailConfirmacao([
    'email' => 'adailtonvieira0809@gmail.com',
    'nome' => 'Nome Teste',
    'setor' => 'TI',
    'data_evento' => '25/11/2025',
    'horario' => '08:00 às 10:00',
    'descricao' => 'Teste de envio de e-mail'
]);

echo $teste ? '✅ E-mail enviado!' : '❌ Falha no envio';
?>