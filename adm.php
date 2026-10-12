<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Agendamentos</title>
    <link rel="icon" type="image/png" href="Nova Logo HEJ Reduzido Branco.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            color: #333;
        }

        header {
            background: #00766f;
            color: white;
            margin-bottom: 40px;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 118, 111, 0.3);
        }

        header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: #FFD700;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 110px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .logo img {
            height: 55px;
            display: block;
        }

        .header-text h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        .header-text p {
            margin: 0;
            font-size: 1rem;
            opacity: 0.9;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-title {
            text-align: center;
            color: #00766f;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #666;
            font-size: 1rem;
        }

        .stat-pendente .stat-number {
            color: #f39c12;
        }

        .stat-aprovado .stat-number {
            color: #27ae60;
        }

        .stat-recusado .stat-number {
            color: #e74c3c;
        }

        .filters {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-group label {
            font-weight: 600;
            color: #00766f;
        }

        .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-pdf {
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-pdf:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
        }

        .btn-pdf:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .loading-pdf {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .requests-container {
            display: grid;
            gap: 20px;
        }

        .request-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #ddd;
            transition: transform 0.2s;
        }

        .request-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .request-card.pendente {
            border-left-color: #f39c12;
        }

        .request-card.aprovado {
            border-left-color: #27ae60;
        }

        .request-card.recusado {
            border-left-color: #e74c3c;
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .request-info h3 {
            color: #00766f;
            margin-bottom: 5px;
            font-size: 1.3rem;
        }

        .request-date {
            color: #666;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pendente {
            background: #fff3cd;
            color: #856404;
        }

        .status-aprovado {
            background: #d4edda;
            color: #155724;
        }

        .status-recusado {
            background: #f8d7da;
            color: #721c24;
        }

        .request-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .detail-label {
            font-weight: 600;
            color: #00766f;
            font-size: 0.9rem;
        }

        .detail-value {
            color: #333;
        }

        .request-description {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .request-description strong {
            color: #00766f;
            display: block;
            margin-bottom: 8px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-approve {
            background: #27ae60;
            color: white;
        }

        .btn-approve:hover {
            background: #229954;
        }

        .btn-reject {
            background: #e74c3c;
            color: white;
        }

        .btn-reject:hover {
            background: #c0392b;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: #00766f;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #666;
        }

        footer {
            text-align: center;
            padding: 40px 20px;
            color: #00766f;
            margin-top: 60px;
            border-top: 2px solid #ddd;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }

            .header-left {
                flex-direction: column;
            }

            .header-right {
                margin-top: 15px;
            }

            .request-header {
                flex-direction: column;
            }

            .action-buttons {
                width: 100%;
            }

            .btn {
                flex: 1;
            }

            .filters {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-pdf {
                margin-left: 0;
                justify-content: center;
            }
        }

        .calendar-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .calendar-header h3 {
            color: #00766f;
            font-size: 1.2rem;
        }

        .calendar-nav {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .calendar-nav button {
            background: #00766f;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        .calendar-nav button:hover {
            background: #005a54;
        }

        .calendar-month {
            font-weight: 600;
            color: #00766f;
            min-width: 130px;
            text-align: center;
            font-size: 0.95rem;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }

        .calendar-day-header {
            text-align: center;
            font-weight: 600;
            color: #00766f;
            padding: 6px;
            font-size: 0.75rem;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
            position: relative;
            padding: 3px;
            min-height: 50px;
        }

        .calendar-day:hover {
            border-color: #00766f;
            background: #f0f9f8;
        }

        .calendar-day.disabled {
            color: #ccc;
            cursor: not-allowed;
            background: #f9f9f9;
        }

        .calendar-day.disabled:hover {
            border-color: #e0e0e0;
            background: #f9f9f9;
        }

        .calendar-day.selected {
            background: #00766f;
            color: white;
            border-color: #00766f;
        }

        .calendar-day-number {
            font-size: 0.9rem;
        }

        .calendar-day-badges {
            display: flex;
            gap: 2px;
            margin-top: 3px;
        }

        .calendar-badge {
            width: 5px;
            height: 5px;
            border-radius: 50%;
        }

        .badge-pendente {
            background: #f39c12;
        }

        .badge-aprovado {
            background: #27ae60;
        }

        .badge-recusado {
            background: #e74c3c;
        }

        .day-details-section {
            display: none;
            margin-top: 30px;
            padding: 25px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .day-details-section.active {
            display: block;
        }

        .day-details-header {
            color: #00766f;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        /* NOVO LAYOUT COMPACTO PARA A TIMELINE */
        .day-timeline-compact {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }

        .timeline-slot-compact {
            display: flex;
            flex-direction: column;
            padding: 8px;
            background: white;
            border-radius: 6px;
            border-left: 3px solid #e0e0e0;
            font-size: 0.8rem;
            min-height: 60px;
        }

        .timeline-slot-compact.pendente {
            border-left-color: #f39c12;
            background: #fff3cd;
        }

        .timeline-slot-compact.aprovado {
            border-left-color: #27ae60;
            background: #d4edda;
        }

        .timeline-slot-compact.recusado {
            border-left-color: #e74c3c;
            background: #f8d7da;
        }

        .timeline-slot-compact.disponivel {
            border-left-color: #3498db;
            background: #d6eaf8;
        }

        .timeline-time-compact {
            font-weight: 600;
            color: #00766f;
            margin-bottom: 3px;
            font-size: 0.75rem;
        }

        .timeline-info-compact {
            flex: 1;
        }

        .timeline-name-compact {
            font-weight: 600;
            margin-bottom: 2px;
            font-size: 0.7rem;
            line-height: 1.2;
        }

        .timeline-setor-compact {
            font-size: 0.65rem;
            color: #666;
            margin-bottom: 3px;
        }

        .timeline-status-compact {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 0.65rem;
            font-weight: 600;
            text-align: center;
            margin-top: 3px;
        }

        .timeline-slot-compact.disponivel .timeline-name-compact {
            color: #3498db;
        }

        /* Layout detalhado original */
        .day-timeline {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .timeline-slot {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            border-left: 4px solid #e0e0e0;
        }

        .timeline-slot.pendente {
            border-left-color: #f39c12;
        }

        .timeline-slot.aprovado {
            border-left-color: #27ae60;
        }

        .timeline-slot.recusado {
            border-left-color: #e74c3c;
        }

        .timeline-slot.disponivel {
            border-left-color: #3498db;
        }

        .timeline-time {
            font-weight: 600;
            color: #00766f;
            min-width: 60px;
        }

        .timeline-info {
            flex: 1;
        }

        .timeline-name {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .timeline-setor {
            font-size: 0.9rem;
            color: #666;
        }

        .timeline-status {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Switch entre visualizações */
        .view-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .view-toggle-btn {
            padding: 6px 12px;
            border: 1px solid #00766f;
            background: white;
            color: #00766f;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s;
        }

        .view-toggle-btn.active {
            background: #00766f;
            color: white;
        }

        .view-toggle-btn:hover {
            background: #005a54;
            color: white;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        }

        .notification.success {
            background: #27ae60;
        }

        .notification.error {
            background: #e74c3c;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .multi-day-badge {
            background: #f39c12;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.7rem;
            margin-left: 5px;
        }

        .date-range-info {
            background: #e8f5f4;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: #00766f;
        }

        /* ESTILOS DO MODAL DE RECUSA */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            text-align: center;
        }

        .modal-content h2 {
            color: #e74c3c;
            margin-bottom: 15px;
            text-align: center;
        }

        .modal-content p {
            line-height: 1.6;
            margin-bottom: 20px;
            text-align: center;
            color: #666;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .btn-cancel {
            background: #95a5a6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }

        .btn-confirm-reject {
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }

        .btn-cancel:hover {
            background: #7f8c8d;
        }

        .btn-confirm-reject:hover {
            background: #c0392b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: Arial, sans-serif;
            font-size: 1rem;
            resize: vertical;
            min-height: 100px;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #00766f;
            box-shadow: 0 0 0 2px rgba(0, 118, 111, 0.1);
        }
    </style>
</head>

<body>

    <header>
        <div class="header-content">
            <div class="header-left">
                <div class="logo" onclick="location.reload()" title="Clique para atualizar a página">
                    <img src="HEJ.png" alt="Logo HEJ" alt="PEP MV"
                        style="width: 120px; height: 50px; display: block; margin: 0 auto;">
                </div>
                <div class="header-text">
                    <h1>Painel Administrativo</h1>
                    <p>Gerenciamento de Agendamentos do Auditório</p>
                </div>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <span>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" class="btn-logout">Sair</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">

        <h2 class="page-title">Solicitações de Agendamento</h2>

        <div class="stats-container">
            <div class="stat-card stat-pendente">
                <div class="stat-number" id="statPendente">0</div>
                <div class="stat-label">Pendentes</div>
            </div>
            <div class="stat-card stat-aprovado">
                <div class="stat-number" id="statAprovado">0</div>
                <div class="stat-label">Aprovados</div>
            </div>
            <div class="stat-card stat-recusado">
                <div class="stat-number" id="statRecusado">0</div>
                <div class="stat-label">Recusados</div>
            </div>
        </div>

        <div class="calendar-section">
            <div class="calendar-header">
                <h3>Visualização por Calendário</h3>
                <div class="calendar-nav">
                    <button onclick="changeMonth(-1)">☚ Anterior</button>
                    <div class="calendar-month" id="calendarMonth"></div>
                    <button onclick="changeMonth(1)">Próximo ☛</button>
                </div>
            </div>

            <div class="calendar-grid" id="calendarGrid">
                <!-- Calendário será gerado aqui -->
            </div>

            <div class="day-details-section" id="dayDetailsSection">
                <h6 class="day-details-header" id="dayDetailsHeader"></h6>

                <div class="view-toggle">
                    <button class="view-toggle-btn active" onclick="toggleView('compact')">Visualização
                        Compacta</button>
                    <button class="view-toggle-btn" onclick="toggleView('detailed')">Visualização Detalhada</button>
                </div>

                <div class="day-timeline" id="dayTimeline">
                    <!-- Timeline do dia será gerada aqui -->
                </div>
            </div>
        </div>

        <div class="filters">
            <div class="filter-group">
                <label for="filterStatus">Filtrar por Status:</label>
                <select id="filterStatus" onchange="filterRequests()">
                    <option value="todos">Todos</option>
                    <option value="pendente">Pendentes</option>
                    <option value="aprovado">Aprovados</option>
                    <option value="recusado">Recusados</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filterDate">Ordenar por:</label>
                <select id="filterDate" onchange="filterRequests()">
                    <option value="recente">Mais Recentes (Padrão)</option>
                    <option value="antigo">Mais Antigos</option>
                    <option value="data-evento">Data do Evento</option>
                </select>
            </div>

            <!-- BOTÃO GERAR PDF -->
            <button class="btn btn-pdf" onclick="gerarPDF()" style="margin-left: auto;">
                GERAR PDF
            </button>
        </div>

        <div class="requests-container" id="requestsContainer">
            <div class="empty-state">
                <div class="empty-state-icon">⏳</div>
                <h3>Carregando solicitações...</h3>
                <p>Aguarde enquanto buscamos os dados.</p>
            </div>
        </div>

    </div>

    <!-- Modal para recusa -->
    <div class="modal" id="rejectModal">
        <div class="modal-content">
            <div class="modal-icon">❌</div>
            <h2>Motivo da Recusa</h2>
            <p>Informe o motivo pelo qual este agendamento está sendo recusado:</p>

            <div class="form-group">
                <label for="motivoRecusa">Motivo da Recusa:</label>
                <textarea id="motivoRecusa" name="motivoRecusa" rows="4"
                    placeholder="Digite o motivo da recusa..."></textarea>
            </div>

            <div class="modal-buttons">
                <button class="btn-cancel" onclick="fecharModalRecusa()">Cancelar</button>
                <button class="btn-confirm-reject" onclick="confirmarRecusa()">Confirmar Recusa</button>
            </div>
        </div>
    </div>

    <footer>
        <img src="https://indicadores.saude.go.gov.br/pentaho/api/repos/pentaho-cdf-dd/static/logo/logo_normal.png"
            alt="Secretaria de Saúde de Goiás" style="max-width: 200px; margin-bottom: 15px;">
        <p>&copy; 2025 Hospital Estadual de Jataí </p>
    </footer>

    <!-- Scripts do jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Variáveis globais para controle da recusa
        let agendamentoRecusaId = null;

        // Função para abrir modal de recusa
        function abrirModalRecusa(id) {
            agendamentoRecusaId = id;
            document.getElementById('rejectModal').classList.add('active');
            document.getElementById('motivoRecusa').value = '';
            document.getElementById('motivoRecusa').focus();
        }

        // Função para fechar modal de recusa
        function fecharModalRecusa() {
            document.getElementById('rejectModal').classList.remove('active');
            agendamentoRecusaId = null;
        }

        // Função para confirmar recusa
        async function confirmarRecusa() {
            const motivo = document.getElementById('motivoRecusa').value.trim();

            if (!motivo) {
                alert('Por favor, informe o motivo da recusa.');
                return;
            }

            if (!agendamentoRecusaId) {
                alert('Erro: ID do agendamento não encontrado.');
                return;
            }

            try {
                // Mostrar loading
                const btnConfirmar = document.querySelector('.btn-confirm-reject');
                const textoOriginal = btnConfirmar.innerHTML;
                btnConfirmar.innerHTML = '⏳ Enviando...';
                btnConfirmar.disabled = true;

                const result = await updateStatusOnServer(agendamentoRecusaId, 'recusado', motivo);

                if (result.success) {
                    showNotification('Agendamento recusado com sucesso! E-mail enviado.');
                    fecharModalRecusa();
                    // Recarrega os dados atualizados
                    await loadRequests();
                    if (selectedDate) {
                        renderDayDetails();
                    }
                } else {
                    showNotification('Erro ao recusar agendamento: ' + (result.error || 'Erro desconhecido'), 'error');
                }

                // Restaurar botão
                btnConfirmar.innerHTML = textoOriginal;
                btnConfirmar.disabled = false;

            } catch (error) {
                console.error('Erro ao recusar agendamento:', error);
                showNotification('Erro ao recusar agendamento. Verifique o console para mais detalhes.', 'error');

                // Restaurar botão em caso de erro
                const btnConfirmar = document.querySelector('.btn-confirm-reject');
                btnConfirmar.innerHTML = 'Confirmar Recusa';
                btnConfirmar.disabled = false;
            }
        }

        // Adicionar evento para fechar modal com ESC
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                fecharModalRecusa();
            }
        });

        // Adicionar evento para fechar modal clicando fora
        document.addEventListener('click', function (event) {
            const modal = document.getElementById('rejectModal');
            if (event.target === modal) {
                fecharModalRecusa();
            }
        });

        let currentDate = new Date();
        let selectedDate = null;
        let allAgendamentos = [];
        let currentView = 'compact';

        //Gerando todos os horários de 30 em 30 minutos (24h)
        const timeSlots = [];
        for (let hour = 0; hour < 24; hour++) {
            for (let minute = 0; minute < 60; minute += 30) {
                const startTime = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                // Para o último horário do dia (23:30), o fim é 23:59
                const endTime = (hour === 23 && minute === 30) ? '23:59' :
                    `${hour.toString().padStart(2, '0')}:${(minute + 29).toString().padStart(2, '0')}`;
                timeSlots.push({
                    start: startTime,
                    end: endTime,
                    display: `${startTime}-${endTime}`
                });
            }
        }

        //Função para formatar data no padrão DD/MM/AAAA - CORRIGIDA
        function formatDate(date) {
            if (!date) return '';

            //converter formato de data corretamente
            if (typeof date === 'string' && date.includes('-')) {
                const partes = date.split('-');
                if (partes.length === 3) {
                    return `${partes[2]}/${partes[1]}/${partes[0]}`;
                }
            }

            //Se for objeto Date, usar método seguro
            const dataObj = new Date(date);
            // Ajustar para o fuso horário local
            const dia = dataObj.getDate().toString().padStart(2, '0');
            const mes = (dataObj.getMonth() + 1).toString().padStart(2, '0');
            const ano = dataObj.getFullYear();
            return `${dia}/${mes}/${ano}`;
        }

        // Função para mostrar notificação
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Função para carregar agendamentos do servidor
        async function loadAgendamentosFromServer() {
            try {
                console.log('Carregando agendamentos do servidor...');
                const response = await fetch('get_bookings.php');

                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }

                const data = await response.json();
                console.log('Resposta do servidor:', data);

                if (data.success && data.agendamentos) {
                    //CORREÇÃO: Ordenar por data de solicitação (MAIS RECENTES PRIMEIRO)
                    const agendamentosOrdenados = data.agendamentos.sort((a, b) => {
                        //Usar dataSolicitacao se existir, caso contrário usar data_criacao ou timestamp atual
                        const dataA = a.dataSolicitacao || a.data_criacao || new Date().toISOString();
                        const dataB = b.dataSolicitacao || b.data_criacao || new Date().toISOString();
                        return new Date(dataB) - new Date(dataA); // Ordem decrescente (mais recente primeiro)
                    });
                    return agendamentosOrdenados;
                } else {
                    throw new Error(data.error || 'Resposta inválida do servidor');
                }
            } catch (error) {
                console.error('Erro ao carregar do servidor:', error);
                showNotification('Erro ao carregar dados: ' + error.message, 'error');
                return [];
            }
        }

        //Função para atualizar status no servidor
        async function updateStatusOnServer(id, novoStatus, motivo = '') {
            try {
                console.log('Atualizando status:', { id, novoStatus, motivo });

                const response = await fetch('update_booking_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: id,
                        status: novoStatus,
                        motivo: motivo
                    })
                });

                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }

                const result = await response.json();
                console.log('Resposta da atualização:', result);

                return result;
            } catch (error) {
                console.error('Erro ao atualizar status:', error);
                return { success: false, error: error.message };
            }
        }

        function toggleView(view) {
            currentView = view;
            document.querySelectorAll('.view-toggle-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            renderDayDetails();
        }

        function renderCalendar(agendamentos = []) {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
            document.getElementById('calendarMonth').textContent = `${monthNames[month]} ${year}`;

            const grid = document.getElementById('calendarGrid');
            grid.innerHTML = '';

            const dayHeaders = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
            dayHeaders.forEach(day => {
                const header = document.createElement('div');
                header.className = 'calendar-day-header';
                header.textContent = day;
                grid.appendChild(header);
            });

            const firstDay = new Date(year, month, 1).getDay();
            const lastDate = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day disabled';
                grid.appendChild(emptyDay);
            }

            for (let day = 1; day <= lastDate; day++) {
                const dayDate = new Date(year, month, day);
                const dayDiv = document.createElement('div');
                dayDiv.className = 'calendar-day';

                //Usar data local para evitar problemas de fuso horário
                const dateStr = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                const dayBookings = agendamentos.filter(a => {
                    //Verificar se o agendamento ocorre neste dia
                    const dataInicio = a.data_inicio || a.data_evento;
                    const dataFim = a.data_fim || dataInicio;

                    const dataInicioObj = new Date(dataInicio + 'T00:00:00');
                    const dataFimObj = new Date(dataFim + 'T23:59:59');
                    const currentDayObj = new Date(dateStr + 'T00:00:00');

                    return currentDayObj >= dataInicioObj && currentDayObj <= dataFimObj;
                });

                const dayNumber = document.createElement('div');
                dayNumber.className = 'calendar-day-number';
                dayNumber.textContent = day;
                dayDiv.appendChild(dayNumber);

                if (dayBookings.length > 0) {
                    const badgesDiv = document.createElement('div');
                    badgesDiv.className = 'calendar-day-badges';

                    const hasPendente = dayBookings.some(a => a.status === 'pendente');
                    const hasAprovado = dayBookings.some(a => a.status === 'aprovado');
                    const hasRecusado = dayBookings.some(a => a.status === 'recusado');

                    if (hasPendente) {
                        const badge = document.createElement('div');
                        badge.className = 'calendar-badge badge-pendente';
                        badgesDiv.appendChild(badge);
                    }
                    if (hasAprovado) {
                        const badge = document.createElement('div');
                        badge.className = 'calendar-badge badge-aprovado';
                        badgesDiv.appendChild(badge);
                    }
                    if (hasRecusado) {
                        const badge = document.createElement('div');
                        badge.className = 'calendar-badge badge-recusado';
                        badgesDiv.appendChild(badge);
                    }

                    dayDiv.appendChild(badgesDiv);
                }

                dayDiv.onclick = () => selectDate(dayDate);

                if (selectedDate && dayDate.toDateString() === selectedDate.toDateString()) {
                    dayDiv.classList.add('selected');
                }

                grid.appendChild(dayDiv);
            }
        }

        function selectDate(date) {
            selectedDate = date;
            renderCalendar(allAgendamentos);
            renderDayDetails();
            document.getElementById('dayDetailsSection').classList.add('active');
        }

        function renderDayDetails() {
            if (!selectedDate) return;

            //Usar data local formatada corretamente
            const dateStr = `${selectedDate.getFullYear()}-${(selectedDate.getMonth() + 1).toString().padStart(2, '0')}-${selectedDate.getDate().toString().padStart(2, '0')}`;
            const formattedDate = selectedDate.toLocaleDateString('pt-BR', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            document.getElementById('dayDetailsHeader').textContent = `📋 Agendamentos - ${formattedDate}`;

            const dayBookings = allAgendamentos.filter(a => {
                // Verificar se o agendamento ocorre neste dia específico
                const dataInicio = a.data_inicio || a.data_evento;
                const dataFim = a.data_fim || dataInicio;

                const dataInicioObj = new Date(dataInicio + 'T00:00:00');
                const dataFimObj = new Date(dataFim + 'T23:59:59');
                const currentDayObj = new Date(dateStr + 'T00:00:00');

                return currentDayObj >= dataInicioObj && currentDayObj <= dataFimObj;
            });

            const timeline = document.getElementById('dayTimeline');
            timeline.innerHTML = '';

            if (currentView === 'compact') {
                timeline.className = 'day-timeline-compact';

                timeSlots.forEach(slot => {
                    const booking = dayBookings.find(a => {
                        const slotStart = slot.start;
                        const slotEnd = slot.end;
                        const bookingStart = a.horario_inicio;
                        const bookingEnd = a.horario_fim;

                        // Verificar se o slot está dentro do horário do agendamento
                        //em agendamentos multi-dia, considerar apenas a parte do horário neste dia específico
                        const isStartDay = dateStr === (a.data_inicio || a.data_evento);
                        const isEndDay = dateStr === a.data_fim;

                        let slotInicio = slotStart;
                        let slotFinal = slotEnd;

                        // Se for o primeiro dia do agendamento multi-dia, considerar apenas a partir do horário de início
                        if (isStartDay && !isEndDay && a.data_fim && a.data_fim !== a.data_inicio) {
                            if (slotStart < bookingStart) return false;
                        }

                        // Se for o último dia do agendamento multi-dia, considerar apenas até o horário de fim
                        if (isEndDay && !isStartDay && a.data_fim && a.data_fim !== a.data_inicio) {
                            if (slotEnd > bookingEnd) return false;
                        }

                        // Verifica se há sobreposição
                        return !(slotFinal <= bookingStart || slotInicio >= bookingEnd);
                    });

                    const slotDiv = document.createElement('div');

                    if (booking) {
                        slotDiv.className = `timeline-slot-compact ${booking.status}`;

                        // Verificar se é reserva multi-dia
                        const isMultiDay = booking.data_fim && booking.data_fim !== (booking.data_inicio || booking.data_evento);

                        slotDiv.innerHTML = `
                            <div class="timeline-time-compact">${slot.start}</div>
                            <div class="timeline-info-compact">
                                <div class="timeline-name-compact">
                                    ${booking.nome}
                                    ${isMultiDay ? '<span class="multi-day-badge">MULTI-DIA</span>' : ''}
                                </div>
                                <div class="timeline-setor-compact">${booking.setor || 'Não informado'}</div>
                            </div>
                            <span class="timeline-status-compact status-${booking.status}">
                                ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
                            </span>
                        `;
                    } else {
                        slotDiv.className = 'timeline-slot-compact disponivel';
                        slotDiv.innerHTML = `
                            <div class="timeline-time-compact">${slot.start}</div>
                            <div class="timeline-info-compact">
                                <div class="timeline-name-compact">Disponível</div>
                                <div class="timeline-setor-compact">${slot.start}-${slot.end}</div>
                            </div>
                            <span class="timeline-status-compact" style="background: #3498db; color: white;">
                                Livre
                            </span>
                        `;
                    }

                    timeline.appendChild(slotDiv);
                });
            } else {
                timeline.className = 'day-timeline';

                timeSlots.forEach(slot => {
                    const booking = dayBookings.find(a => {
                        const slotStart = slot.start;
                        const slotEnd = slot.end;
                        const bookingStart = a.horario_inicio;
                        const bookingEnd = a.horario_fim;

                        // Verificar se o slot está dentro do horário do agendamento
                        // Para agendamentos multi-dia, considerar apenas a parte do horário neste dia específico
                        const isStartDay = dateStr === (a.data_inicio || a.data_evento);
                        const isEndDay = dateStr === a.data_fim;

                        let slotInicio = slotStart;
                        let slotFinal = slotEnd;

                        // Se for o primeiro dia do agendamento multi-dia, considerar apenas a partir do horário de início
                        if (isStartDay && !isEndDay && a.data_fim && a.data_fim !== a.data_inicio) {
                            if (slotStart < bookingStart) return false;
                        }

                        // Se for o último dia do agendamento multi-dia, considerar apenas até o horário de fim
                        if (isEndDay && !isStartDay && a.data_fim && a.data_fim !== a.data_inicio) {
                            if (slotEnd > bookingEnd) return false;
                        }

                        // Verifica se há sobreposição
                        return !(slotFinal <= bookingStart || slotInicio >= bookingEnd);
                    });

                    const slotDiv = document.createElement('div');

                    if (booking) {
                        slotDiv.className = `timeline-slot ${booking.status}`;

                        // Verificar se é reserva multi-dia
                        const isMultiDay = booking.data_fim && booking.data_fim !== (booking.data_inicio || booking.data_evento);

                        slotDiv.innerHTML = `
                            <div class="timeline-time">${slot.start}</div>
                            <div class="timeline-info">
                                <div class="timeline-name">
                                    ${booking.nome}
                                    ${isMultiDay ? '<span class="multi-day-badge">RESERVA MULTI-DIA</span>' : ''}
                                </div>
                                <div class="timeline-setor">${booking.setor || 'Não informado'}</div>
                            </div>
                            <span class="timeline-status status-${booking.status}">
                                ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
                            </span>
                        `;
                    } else {
                        slotDiv.className = 'timeline-slot disponivel';
                        slotDiv.innerHTML = `
                            <div class="timeline-time">${slot.start}</div>
                            <div class="timeline-info">
                                <div class="timeline-name" style="color: #3498db;">Disponível</div>
                                <div class="timeline-setor">${slot.start} - ${slot.end}</div>
                            </div>
                            <span class="timeline-status" style="background: #3498db; color: white;">
                                Livre
                            </span>
                        `;
                    }

                    timeline.appendChild(slotDiv);
                });
            }
        }

        function changeMonth(delta) {
            currentDate.setMonth(currentDate.getMonth() + delta);
            loadRequests();
            document.getElementById('dayDetailsSection').classList.remove('active');
            selectedDate = null;
        }

        async function loadRequests() {
            try {
                const container = document.getElementById('requestsContainer');
                container.classList.add('loading');

                const agendamentos = await loadAgendamentosFromServer();
                allAgendamentos = agendamentos;

                // Atualiza estatísticas
                const stats = {
                    pendente: agendamentos.filter(a => a.status === 'pendente').length,
                    aprovado: agendamentos.filter(a => a.status === 'aprovado').length,
                    recusado: agendamentos.filter(a => a.status === 'recusado').length
                };

                document.getElementById('statPendente').textContent = stats.pendente;
                document.getElementById('statAprovado').textContent = stats.aprovado;
                document.getElementById('statRecusado').textContent = stats.recusado;

                filterRequests();
                renderCalendar(agendamentos);

                container.classList.remove('loading');
            } catch (error) {
                console.error('Erro ao carregar requisições:', error);
                showNotification('Erro ao carregar solicitações', 'error');
            }
        }

        function filterRequests() {
            const filterStatus = document.getElementById('filterStatus').value;
            const filterDate = document.getElementById('filterDate').value;

            // Usar allAgendamentos em vez de parâmetro
            let filtered = [...allAgendamentos];

            if (filterStatus !== 'todos') {
                filtered = filtered.filter(a => a.status === filterStatus);
            }

            // Aplicar ordenação conforme seleção
            if (filterDate === 'antigo') {
                filtered.sort((a, b) => {
                    const dataA = a.dataSolicitacao || a.data_criacao || new Date().toISOString();
                    const dataB = b.dataSolicitacao || b.data_criacao || new Date().toISOString();
                    return new Date(dataA) - new Date(dataB); // Ordem crescente (mais antigo primeiro)
                });
            } else if (filterDate === 'data-evento') {
                filtered.sort((a, b) => {
                    const dataA = a.data_inicio || a.data_evento;
                    const dataB = b.data_inicio || b.data_evento;
                    return new Date(dataA) - new Date(dataB); // Ordem crescente por data do evento
                });
            } else {
                // Padrão: mais recentes primeiro (já vem ordenado do servidor)
                // Não precisa reordenar pois já vem ordenado por dataSolicitacao decrescente
            }

            renderRequests(filtered);
        }

        function renderRequests(agendamentos) {
            const container = document.getElementById('requestsContainer');

            if (agendamentos.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <h3>Nenhuma solicitação encontrada</h3>
                        <p>Não há solicitações de agendamento com os filtros selecionados.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = agendamentos.map(agendamento => {
                // CORREÇÃO: Formatar data de solicitação corretamente
                let dataSolicitacaoFormatada = 'Data não disponível';
                try {
                    const dataSolicitacao = agendamento.dataSolicitacao || agendamento.data_criacao;
                    if (dataSolicitacao) {
                        dataSolicitacaoFormatada = new Date(dataSolicitacao).toLocaleString('pt-BR');
                    }
                } catch (e) {
                    console.error('Erro ao formatar data:', e);
                }

                // Usar data_inicio em vez de data_evento para compatibilidade
                const dataInicio = agendamento.data_inicio || agendamento.data_evento;
                const dataEvento = new Date(dataInicio + 'T00:00:00').toLocaleDateString('pt-BR');

                // CORREÇÃO: Garantir que horário_inicio e horario_fim existam
                const horarioInicio = agendamento.horario_inicio || 'Não definido';
                const horarioFim = agendamento.horario_fim || 'Não definido';

                // Verificar se é reserva multi-dia
                const isMultiDay = agendamento.data_fim && agendamento.data_fim !== dataInicio;
                const dataFimFormatada = isMultiDay ?
                    new Date(agendamento.data_fim + 'T00:00:00').toLocaleDateString('pt-BR') : '';

                return `
                    <div class="request-card ${agendamento.status}" data-id="${agendamento.id}">
                        <div class="request-header">
                            <div class="request-info">
                                <h3>${agendamento.nome} 
                                    ${isMultiDay ? '<span class="multi-day-badge">RESERVA MULTI-DIA</span>' : ''}
                                </h3>
                                <div class="request-date">Solicitado em: ${dataSolicitacaoFormatada}</div>
                            </div>
                            <span class="status-badge status-${agendamento.status}">
                                ${agendamento.status.toUpperCase()}
                            </span>
                        </div>
                        
                        ${isMultiDay ? `
                        <div class="date-range-info">
                            📅 <strong>Período:</strong> ${formatDate(dataInicio)} a ${formatDate(agendamento.data_fim)}
                        </div>
                        ` : ''}
                        
                        <div class="request-details">
                            <div class="detail-item">
                                <span class="detail-label">Setor</span>
                                <span class="detail-value">${agendamento.setor || 'Não informado'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Celular</span>
                                <span class="detail-value">${agendamento.celular || 'Não informado'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">E-mail</span>
                                <span class="detail-value">${agendamento.email}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">${isMultiDay ? 'Data de Início' : 'Data do Evento'}</span>
                                <span class="detail-value">
                                    ${formatDate(dataInicio)}
                                    ${isMultiDay ? ` até ${formatDate(agendamento.data_fim)}` : ''}
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Horário</span>
                                <span class="detail-value">${horarioInicio} - ${horarioFim}</span>
                            </div>
                        </div>
                        
                        <div class="request-description">
                            <strong>Descrição do Evento:</strong>
                            ${agendamento.descricao || 'Não informado'}
                        </div>
                        
                        <div class="action-buttons">
                            <button 
                                class="btn btn-approve" 
                                onclick="updateStatus('${agendamento.id}', 'aprovado')"
                                ${agendamento.status !== 'pendente' ? 'disabled' : ''}
                            >
                                ✓ Aprovar
                            </button>
                            <button 
                                class="btn btn-reject" 
                                onclick="updateStatus('${agendamento.id}', 'recusado')"
                                ${agendamento.status !== 'pendente' ? 'disabled' : ''}
                            >
                                ✗ Recusar
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Modificar a função updateStatus para lidar com recusa
        async function updateStatus(id, novoStatus, motivo = '') {
            if (novoStatus === 'recusado') {
                // Para recusa, abrir modal para coletar motivo
                abrirModalRecusa(id);
                return;
            }

            // Para aprovação, proceder normalmente
            const acao = 'APROVAR';

            if (!confirm(`Tem certeza que deseja ${acao} esta solicitação?`)) {
                return;
            }

            try {
                const result = await updateStatusOnServer(id, novoStatus, motivo);

                if (result.success) {
                    showNotification(`Solicitação ${novoStatus === 'aprovado' ? 'aprovada' : 'recusada'} com sucesso! E-mail enviado.`);
                    await loadRequests();
                    if (selectedDate) {
                        renderDayDetails();
                    }
                } else {
                    showNotification('Erro ao atualizar status: ' + (result.error || 'Erro desconhecido'), 'error');
                }
            } catch (error) {
                console.error('Erro ao atualizar status:', error);
                showNotification('Erro ao atualizar status. Verifique o console para mais detalhes.', 'error');
            }
        }

        // ========== FUNÇÕES DO PDF ==========

        // Verificar se jsPDF está carregado
        function checkJsPDF() {
            if (typeof window.jspdf === 'undefined') {
                console.error('jsPDF não carregado');
                return false;
            }
            return true;
        }

        // Função para gerar PDF
        async function gerarPDF() {
            if (!checkJsPDF()) {
                showNotification('Erro: Biblioteca PDF não carregada', 'error');
                return;
            }

            const pdfButton = document.querySelector('.btn-pdf');
            const originalText = pdfButton.innerHTML;

            try {
                // Mostrar loading no botão
                pdfButton.innerHTML = '⏳ Gerando PDF...';
                pdfButton.disabled = true;

                showNotification('Iniciando geração do relatório PDF...', 'success');

                // Aguardar um pouco para garantir que a UI atualize
                await new Promise(resolve => setTimeout(resolve, 500));

                // Criar instância do jsPDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Configurações do relatório
                const filterStatus = document.getElementById('filterStatus').value;
                const filterDate = document.getElementById('filterDate').value;

                // Dados para o PDF
                const dataAtual = new Date().toLocaleDateString('pt-BR');
                const horaAtual = new Date().toLocaleTimeString('pt-BR');

                // Cabeçalho do PDF
                doc.setFontSize(16);
                doc.setTextColor(0, 118, 111);
                doc.text('Relatório de Agendamentos - Auditório', 20, 30);

                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                doc.text(`Hospital Estadual de Jataí`, 20, 40);
                doc.text(`Gerado em: ${dataAtual} às ${horaAtual}`, 20, 46);

                // Informações dos filtros
                doc.text(`Filtros aplicados: Status: ${getStatusText(filterStatus)} | Ordenação: ${getOrdenacaoText(filterDate)}`, 20, 56);

                // Estatísticas
                const stats = {
                    pendente: allAgendamentos.filter(a => a.status === 'pendente').length,
                    aprovado: allAgendamentos.filter(a => a.status === 'aprovado').length,
                    recusado: allAgendamentos.filter(a => a.status === 'recusado').length,
                    total: allAgendamentos.length
                };

                doc.setFontSize(12);
                doc.setTextColor(0, 0, 0);
                doc.text(`Resumo: ${stats.total} agendamentos (${stats.pendente} pendentes, ${stats.aprovado} aprovados, ${stats.recusado} recusados)`, 20, 70);

                // Preparar dados para a tabela
                const tableData = prepareTableData();

                if (tableData.length > 0) {
                    // Adicionar tabela
                    doc.autoTable({
                        startY: 80,
                        head: [['Data Evento', 'Horário', 'Solicitante', 'Setor', 'Status', 'Motivo Recusa', 'Data Solicitação']],
                        body: tableData,
                        styles: { fontSize: 8 },
                        headStyles: {
                            fillColor: [0, 118, 111],
                            textColor: 255,
                            fontStyle: 'bold'
                        },
                        alternateRowStyles: {
                            fillColor: [240, 240, 240]
                        },
                        columnStyles: {
                            0: { cellWidth: 20 },
                            1: { cellWidth: 20 },
                            2: { cellWidth: 25 },
                            3: { cellWidth: 20, halign: 'center' },
                            4: { cellWidth: 20 },
                            5: { cellWidth: 60 },
                            6: { cellWidth: 25, halign: 'right' },
                        },
                        margin: { top: 80 }
                    });
                } else {
                    doc.text('Nenhum agendamento encontrado com os filtros selecionados.', 20, 90);
                }

                // Adicionar estatísticas finais na mesma página
                const finalY = doc.lastAutoTable.finalY || 90;

                if (finalY > 250) {
                    doc.addPage();
                    doc.setFontSize(12);
                    doc.setTextColor(0, 118, 111);
                    doc.text('Estatísticas Finais', 20, 30);

                    doc.setFontSize(10);
                    doc.setTextColor(0, 0, 0);
                    doc.text(`• Total de Agendamentos: ${stats.total}`, 25, 45);
                    doc.text(`• Pendentes: ${stats.pendente}`, 25, 55);
                    doc.text(`• Aprovados: ${stats.aprovado}`, 25, 65);
                    doc.text(`• Recusados: ${stats.recusado}`, 25, 75);
                } else {
                    doc.setFontSize(12);
                    doc.setTextColor(0, 118, 111);
                    doc.text('Estatísticas Finais', 20, finalY + 15);

                    doc.setFontSize(10);
                    doc.setTextColor(0, 0, 0);
                    doc.text(`• Total de Agendamentos: ${stats.total}`, 25, finalY + 30);
                    doc.text(`• Pendentes: ${stats.pendente}`, 25, finalY + 40);
                    doc.text(`• Aprovados: ${stats.aprovado}`, 25, finalY + 50);
                    doc.text(`• Recusados: ${stats.recusado}`, 25, finalY + 60);
                }

                // Nome do arquivo
                const fileName = `relatorio_agendamentos_${dataAtual.replace(/\//g, '-')}.pdf`;

                // Salvar PDF
                doc.save(fileName);

                showNotification('PDF gerado com sucesso!', 'success');

            } catch (error) {
                console.error('Erro ao gerar PDF:', error);
                showNotification('Erro ao gerar PDF: ' + error.message, 'error');
            } finally {
                // Restaurar botão
                pdfButton.innerHTML = originalText;
                pdfButton.disabled = false;
            }
        }

        // Função para preparar dados da tabela
        function prepareTableData() {
            const filterStatus = document.getElementById('filterStatus').value;
            const filterDate = document.getElementById('filterDate').value;

            let filteredData = [...allAgendamentos];

            // Aplicar filtros (igual à função filterRequests)
            if (filterStatus !== 'todos') {
                filteredData = filteredData.filter(a => a.status === filterStatus);
            }

            // Aplicar ordenação
            if (filterDate === 'antigo') {
                filteredData.sort((a, b) => {
                    const dataA = a.dataSolicitacao || a.data_criacao || new Date().toISOString();
                    const dataB = b.dataSolicitacao || b.data_criacao || new Date().toISOString();
                    return new Date(dataA) - new Date(dataB);
                });
            } else if (filterDate === 'data-evento') {
                filteredData.sort((a, b) => {
                    const dataA = a.data_inicio || a.data_evento;
                    const dataB = b.data_inicio || b.data_evento;
                    return new Date(dataA) - new Date(dataB);
                });
            }
            // Para "recente", não precisa reordenar pois já vem ordenado

            // Formatar dados para tabela
            return filteredData.map(agendamento => {
                const dataInicio = agendamento.data_inicio || agendamento.data_evento;
                const dataEvento = formatDate(dataInicio);
                const dataSolicitacao = new Date(agendamento.dataSolicitacao).toLocaleDateString('pt-BR');

                // Verificar se é multi-dia
                const isMultiDay = agendamento.data_fim && agendamento.data_fim !== dataInicio;
                const dataDisplay = isMultiDay ?
                    `${dataEvento} a ${formatDate(agendamento.data_fim)}` :
                    dataEvento;

                // Obter motivo da recusa (se existir)
                const motivoRecusa = agendamento.motivo_recusa || (agendamento.status === 'recusado' ? 'Motivo não informado' : '-');

                return [
                    dataDisplay,
                    `${agendamento.horario_inicio} - ${agendamento.horario_fim}`,
                    agendamento.nome.substring(0, 30), // Limitar tamanho
                    (agendamento.setor || 'Não informado').substring(0, 12),
                    agendamento.status.toUpperCase().substring(0, 9),
                    motivoRecusa.substring(0, 77), // Limitar tamanho do motivo
                    dataSolicitacao
                ];
            });
        }

        // Funções auxiliares
        function getStatusText(status) {
            const statusMap = {
                'todos': 'Todos',
                'pendente': 'Pendentes',
                'aprovado': 'Aprovados',
                'recusado': 'Recusados'
            };
            return statusMap[status] || status;
        }

        function getOrdenacaoText(ordenacao) {
            const ordenacaoMap = {
                'recente': 'Mais Recentes',
                'antigo': 'Mais Antigos',
                'data-evento': 'Data do Evento'
            };
            return ordenacaoMap[ordenacao] || ordenacao;
        }

        function getStatusColor(status) {
            const colors = {
                'pendente': [243, 156, 18],   // Laranja
                'aprovado': [39, 174, 96],    // Verde
                'recusado': [231, 76, 60]     // Vermelho
            };
            return colors[status] || [0, 0, 0]; // Preto padrão
        }

        // ========== FIM DAS FUNÇÕES DO PDF ==========

        // Inicialização
        document.addEventListener('DOMContentLoaded', function () {
            loadRequests();

            // Atualiza a cada 30 segundos
            setInterval(loadRequests, 30000);
        });
    </script>
</body>

</html>