<?php
header('Content-Type: application/json; charset=utf-8');

// Configurações do banco de dados
$host = 'localhost';
$dbname = 'agendamento_auditorio';
$username = '';
$password = '';

try {
    // Conexão com o banco
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Receber dados do formulário
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_fim = $_POST['data_fim'] ?? $data_inicio;
    $horario_inicio = $_POST['horario_inicio'] ?? '';
    $horario_fim = $_POST['horario_fim'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $setor = $_POST['setor'] ?? '';
    $celular = $_POST['celular'] ?? '';
    $email = $_POST['email'] ?? '';
    $descricao = $_POST['descricao'] ?? '';

    // Validar dados obrigatórios
    if (empty($data_inicio) || empty($horario_inicio) || empty($horario_fim) || empty($nome) || empty($setor) || empty($celular) || empty($email)) {
        throw new Exception("Todos os campos obrigatórios devem ser preenchidos");
    }

    // Gerar ID único
    $id = uniqid('ag_', true);
    
    // Inserir no banco de dados
    $query = "INSERT INTO agendamentos (
        id, data_inicio, data_fim, horario_inicio, horario_fim, 
        nome, setor, celular, email, descricao, status, dataSolicitacao
    ) VALUES (
        :id, :data_inicio, :data_fim, :horario_inicio, :horario_fim,
        :nome, :setor, :celular, :email, :descricao, 'pendente', NOW()
    )";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':data_inicio', $data_inicio);
    $stmt->bindValue(':data_fim', $data_fim);
    $stmt->bindValue(':horario_inicio', $horario_inicio);
    $stmt->bindValue(':horario_fim', $horario_fim);
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':setor', $setor);
    $stmt->bindValue(':celular', $celular);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':descricao', $descricao);
    
    if ($stmt->execute()) {
        // Tentar enviar e-mail (mas não bloquear se falhar)
        $emailEnviado = false;
        try {
            $dadosEmail = [
                'email' => $email,
                'nome' => $nome,
                'data_evento' => date('d/m/Y', strtotime($data_inicio)),
                'horario' => $horario_inicio . ' - ' . $horario_fim,
                'setor' => $setor,
                'descricao' => $descricao
            ];
            
            $emailEnviado = enviarEmailConfirmacao($dadosEmail);
        } catch (Exception $emailError) {
            error_log("Erro no e-mail (não crítico): " . $emailError->getMessage());
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Solicitação enviada com sucesso!' . ($emailEnviado ? ' E-mail de confirmação enviado.' : ''),
            'email_enviado' => $emailEnviado
        ]);
    } else {
        throw new Exception("Erro ao salvar no banco de dados");
    }
    
} catch (Exception $e) {
    error_log("Erro save_booking: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit;
}

// Função para enviar e-mail de confirmação
function enviarEmailConfirmacao($dados) {
    // Configurações do e-mail
    $smtp_host = 'smtp.gmail.com';
    $smtp_port = 587;
    $smtp_username = 'adailtonvieira0809@gmail.com';
    $smtp_password = 'gqnk outa duti vgqf';
    $from_email = 'adailtonvieira0809@gmail.com';
    $from_name = 'Hospital Estadual de Jataí';
    
    $para = $dados['email'];
    $assunto = "Confirmação de Solicitação de Agendamento - Hospital Estadual de Jataí";
    
    //e-mail em HTML
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
            .status { background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; text-align: center; font-weight: bold; }
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
                
                <p>Recebemos sua solicitação de agendamento do auditório.</p>
                
                <div class='destaque'>
                    <h3>📅 Detalhes da Solicitação:</h3>
                    <p><strong>Data:</strong> {$dados['data_evento']}</p>
                    <p><strong>Horário:</strong> {$dados['horario']}</p>
                    <p><strong>Setor:</strong> {$dados['setor']}</p>
                    <p><strong>Descrição:</strong> {$dados['descricao']}</p>
                </div>
                
                <div class='status'>
                    ⏳ <strong>Status:</strong> Aguardando Aprovação
                </div>
                
                <p>Sua solicitação será analisada pela administração e você receberá um retorno em breve.</p>
                
                <p><strong>Observações importantes:</strong></p>
                <ul>
                    <li>O horário só será reservado após aprovação</li>
                    <li>Você receberá notificação por e-mail sobre o status da sua solicitação</li>
                    <li>Em caso de dúvidas, entre em contato com a administração</li>
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
    
    Recebemos sua solicitação de agendamento do auditório.
    
    DETALHES DA SOLICITAÇÃO:
    Data: {$dados['data_evento']}
    Horário: {$dados['horario']}
    Setor: {$dados['setor']}
    Descrição: {$dados['descricao']}
    
    STATUS: Aguardando Aprovação
    
    Sua solicitação será analisada pela administração e você receberá um retorno em breve.
    
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
        error_log("✅ E-mail enviado com função nativa para: " . $para);
    } else {
        error_log("❌ Falha ao enviar e-mail com função nativa para: " . $para);
    }
    
    return $result;
}
?>
