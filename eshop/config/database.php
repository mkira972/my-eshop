<?php
class Database {
    private $host = "localhost";
    private $db_name = "ecommerce_db";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            $this->conn->set_charset("utf8mb4");
            
            if ($this->conn->connect_error) {
                die("Erreur de connexion : " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
        
        return $this->conn;
    }
}
?>