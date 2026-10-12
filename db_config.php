<?php
// db_config.php - VERSÃO SEGURA
error_reporting(0);
ini_set('display_errors', 0);

class Database {
    private $host = 'localhost';
    private $dbname = 'agendamento_auditorio';
    private $username = '';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4", 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Não mostra erro, só log
            error_log("Erro de conexão: " . $e->getMessage());
            return null;
        }
        return $this->conn;
    }
}
?>
