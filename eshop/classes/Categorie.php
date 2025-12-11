<?php
require_once './config/database.php';

class Categorie {
    private $conn;
    private $table = "categories";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($nom, $description) {
        $query = "INSERT INTO " . $this->table . " (nom, description) VALUES (?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $nom, $description);
        
        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT c.*, COUNT(pc.produit_id) as nb_produits 
                  FROM " . $this->table . " c 
                  LEFT JOIN produit_categorie pc ON c.id = pc.categorie_id 
                  GROUP BY c.id 
                  ORDER BY c.nom";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    public function update($id, $nom, $description) {
        $query = "UPDATE " . $this->table . " SET nom = ?, description = ? WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $nom, $description, $id);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
}
?>