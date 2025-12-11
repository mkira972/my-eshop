<?php
require_once '../config/database.php';

class User {
    private $conn;
    private $table = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($nom, $prenom, $email, $password, $telephone = '', $adresse = '') {
        $query = "INSERT INTO " . $this->table . " (nom, prenom, email, password, telephone, adresse) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bind_param("ssssss", $nom, $prenom, $email, $password_hash, $telephone, $adresse);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($email, $password) {
        $query = "SELECT id, nom, prenom, email, password, is_admin FROM " . $this->table . " WHERE email = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_prenom'] = $user['prenom'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['is_admin'] = $user['is_admin'];
                return true;
            }
        }
        return false;
    }

    public function getUserById($id) {
        $query = "SELECT id, nom, prenom, email, telephone, adresse, is_admin FROM " . $this->table . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    public function getAllUsers() {
        $query = "SELECT id, nom, prenom, email, telephone, is_admin, date_creation FROM " . $this->table . " ORDER BY date_creation DESC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateUser($id, $nom, $prenom, $email, $telephone, $adresse) {
        $query = "UPDATE " . $this->table . " SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ? WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssi", $nom, $prenom, $email, $telephone, $adresse, $id);
        
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
}
?>