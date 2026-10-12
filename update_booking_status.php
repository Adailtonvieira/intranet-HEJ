<?php
header('Content-Type: application/json; charset=utf-8');

// Incluir o db_config.php que você já tem
include 'db_config.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception("Não foi possível conectar ao banco de dados");
    }

    // Receber dados via JSON
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? '';
    $novoStatus = $input['status'] ?? '';
    $motivo = $input['motivo'] ?? '';

    if (empty($id) || empty($novoStatus)) {
        throw new Exception("ID e status são obrigatórios");
    }

    if (!in_array($novoStatus, ['aprovado', 'recusado'])) {
        throw new Exception("Status inválido");
    }

    // Buscar dados do agendamento antes de atualizar
    $querySelect = "SELECT * FROM agendamentos WHERE id = :id";
    $stmtSelect = $db->prepare($querySelect);
    $stmtSelect->bindValue(':id', $id);
    $stmtSelect->execute();
    $agendamento = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    if (!$agendamento) {
        throw new Exception("Agendamento não encontrado");
    }

    // Atualizar status no banco de dados (e motivo se for recusa)
    if ($novoStatus === 'recusado') {
        $queryUpdate = "UPDATE agendamentos SET status = :status, motivo_recusa = :motivo WHERE id = :id";
        $stmtUpdate = $db->prepare($queryUpdate);
        $stmtUpdate->bindValue(':status', $novoStatus);
        $stmtUpdate->bindValue(':motivo', $motivo);
        $stmtUpdate->bindValue(':id', $id);
    } else {
        $queryUpdate = "UPDATE agendamentos SET status = :status, motivo_recusa = NULL WHERE id = :id";
        $stmtUpdate = $db->prepare($queryUpdate);
        $stmtUpdate->bindValue(':status', $novoStatus);
        $stmtUpdate->bindValue(':id', $id);
    }
    
    if ($stmtUpdate->execute()) {
        // Tentar enviar e-mail (mas não bloquear se falhar)
        $emailEnviado = false;
        try {
            $dadosEmail = [
                'email' => $agendamento['email'],
                'nome' => $agendamento['nome'],
                'data_evento' => date('d/m/Y', strtotime($agendamento['data_inicio'])),
                'horario' => $agendamento['horario_inicio'] . ' - ' . $agendamento['horario_fim'],
                'setor' => $agendamento['setor'],
                'descricao' => $agendamento['descricao'],
                'status' => $novoStatus,
                'observacao' => $motivo
            ];
            
            $emailEnviado = enviarEmailStatus($dadosEmail);
        } catch (Exception $emailError) {
            error_log("Erro no e-mail (não crítico): " . $emailError->getMessage());
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Status atualizado com sucesso!' . ($emailEnviado ? ' E-mail enviado.' : ''),
            'email_enviado' => $emailEnviado
        ]);
    } else {
        throw new Exception("Erro ao atualizar status no banco de dados");
    }
    
} catch (Exception $e) {
    error_log("Erro update_booking_status: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit;
}

// Função para enviar e-mail de status
function enviarEmailStatus($dados) {
    // Configurações do e-mail
    $smtp_host = 'smtp.gmail.com';
    $smtp_port = 587;
    $smtp_username = 'adailtonvieira0809@gmail.com';
    $smtp_password = 'gqnk outa duti vgqf';
    $from_email = 'adailtonvieira0809@gmail.com';
    $from_name = 'Hospital Estadual de Jataí';
    
    $para = $dados['email'];
    
    // Definir assunto e cor baseado no status
    if ($dados['status'] === 'aprovado') {
        $assunto = "✅ Agendamento Aprovado - Hospital Estadual de Jataí";
        $cor_status = '#d4edda';
        $texto_status = '#155724';
        $icone_status = '✅';
        $mensagem_status = 'Seu agendamento foi <strong>APROVADO</strong>';
        $instrucoes = 'O auditório está reservado para você no horário solicitado.';
    } else {
        $assunto = "❌ Agendamento Recusado - Hospital Estadual de Jataí";
        $cor_status = '#f8d7da';
        $texto_status = '#721c24';
        $icone_status = '❌';
        $mensagem_status = 'Seu agendamento foi <strong>RECUSADO</strong>';
        $instrucoes = 'Entre em contato com a administração para mais informações.';
    }
    
    // Corpo do e-mail em HTML
    $mensagem = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
            .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .header { background: #00766f; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { padding: 20px; }
            .footer { background: #f4f4f4; padding: 15px; text-align: center; border-radius: 0 0 8px 8px; margin-top: 20px; }
            .destaque { background: #e8f5f4; padding: 15px; border-left: 4px solid #00766f; margin: 15px 0; }
            .status { background: $cor_status; color: $texto_status; padding: 15px; border-radius: 5px; text-align: center; font-weight: bold; margin: 20px 0; }
            .motivo { background: #f8f9fa; padding: 15px; border-left: 4px solid #6c757d; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Hospital Estadual de Jataí</h1>
                <h2>Agendamento de Auditório</h2>
            </div>
            
            <div class='content'>
                <p>Prezado(a) <strong>{$dados['nome']}</strong>,</p>
                
                <p>Informamos sobre a atualização do status do seu agendamento.</p>
                
                <div class='destaque'>
                    <h3>📅 Detalhes do Agendamento:</h3>
                    <p><strong>Data:</strong> {$dados['data_evento']}</p>
                    <p><strong>Horário:</strong> {$dados['horario']}</p>
                    <p><strong>Setor:</strong> {$dados['setor']}</p>
                    <p><strong>Descrição:</strong> {$dados['descricao']}</p>
                </div>
                
                <div class='status'>
                    $icone_status $mensagem_status
                </div>
                
                " . ($dados['status'] === 'recusado' && !empty($dados['observacao']) ? "
                <div class='motivo'>
                    <h3>📝 Motivo da Recusa:</h3>
                    <p>{$dados['observacao']}</p>
                </div>
                " : "") . "
                
                <p>$instrucoes</p>
                
                <p><strong>Em caso de dúvidas:</strong></p>
                <ul>
                    <li>Entre em contato com a administração do hospital</li>
                    <li>Verifique nosso sistema de agendamento</li>
                </ul>
            </div>
            
            <div class='footer'>
                <p>&copy; " . date('Y') . " Hospital Estadual de Jataí</p>
                <p>Este é um e-mail automático, por favor não responda.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Versão texto simples
    $mensagem_texto = "
    HOSPITAL ESTADUAL DE JATAÍ
    AGENDAMENTO DE AUDITÓRIO
    
    Prezado(a) {$dados['nome']},
    
    Informamos sobre a atualização do status do seu agendamento.
    
    DETALHES DO AGENDAMENTO:
    Data: {$dados['data_evento']}
    Horário: {$dados['horario']}
    Setor: {$dados['setor']}
    Descrição: {$dados['descricao']}
    
    STATUS: " . ($dados['status'] === 'aprovado' ? 'APROVADO' : 'RECUSADO') . "
    
    " . ($dados['status'] === 'recusado' && !empty($dados['observacao']) ? "
    MOTIVO DA RECUSA:
    {$dados['observacao']}
    " : "") . "
    
    " . ($dados['status'] === 'aprovado' ? 
        'O auditório está reservado para você no horário solicitado.' : 
        'Entre em contato com a administração para mais informações.') . "
    
    © " . date('Y') . " Hospital Estadual de Jataí
    ";

    return enviarEmail($para, $assunto, $mensagem, $mensagem_texto, $dados, $smtp_host, $smtp_port, $smtp_username, $smtp_password, $from_email, $from_name);
}

// Função principal para enviar e-mail
function enviarEmail($para, $assunto, $mensagem_html, $mensagem_texto, $dados, $smtp_host, $smtp_port, $smtp_username, $smtp_password, $from_email, $from_name) {
    
    // TENTAR USANDO PHPMailer (RECOMENDADO)
    if (file_exists('PHPMailer/PHPMailer.php')) {
        try {
            require_once 'PHPMailer/PHPMailer.php';
            require_once 'PHPMailer/SMTP.php';
            require_once 'PHPMailer/Exception.php';
            
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = $smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $smtp_username;
            $mail->Password = $smtp_password;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $smtp_port;
            $mail->SMTPDebug = 0;
            
            // Remetente e destinatário
            $mail->setFrom($from_email, $from_name);
            $mail->addAddress($para, $dados['nome']);
            
            // Conteúdo do e-mail
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body = $mensagem_html;
            $mail->AltBody = $mensagem_texto;
            $mail->CharSet = 'UTF-8';
            
            // Enviar e-mail
            $result = $mail->send();
            error_log("✅ E-mail enviado com PHPMailer para: " . $para);
            return $result;
            
        } catch (Exception $e) {
            error_log("❌ Erro PHPMailer: " . $e->getMessage());
            // Tentar método nativo se PHPMailer falhar
            return enviarEmailNativo($para, $assunto, $mensagem_html, $mensagem_texto, $dados, $from_email, $from_name);
        }
    }
    
    // TENTAR USANDO FUNÇÃO NATIVA DO PHP
    error_log("⚠️ PHPMailer não encontrado, usando função nativa");
    return enviarEmailNativo($para, $assunto, $mensagem_html, $mensagem_texto, $dados, $from_email, $from_name);
}

// Função para enviar e-mail usando função nativa
function enviarEmailNativo($para, $assunto, $mensagem_html, $mensagem_texto, $dados, $from_email, $from_name) {
    
    $headers = "From: $from_name <$from_email>\r\n";
    $headers .= "Reply-To: $from_email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    $result = mail($para, $assunto, $mensagem_html, $headers);
    
    if ($result) {
        error_log("✅ E-mail de status enviado com função nativa para: " . $para . " - Status: " . $dados['status']);
    } else {
        error_log("❌ Falha ao enviar e-mail de status com função nativa para: " . $para);
    }
    
    return $result;
}
?>