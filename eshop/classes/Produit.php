<?php
require_once './config/database.php';

class Produit {
    private $conn;
    private $table = "produits";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($nom, $description, $prix, $stock, $image, $categories = []) {
        $query = "INSERT INTO " . $this->table . " (nom, description, prix, stock, image) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssdis", $nom, $description, $prix, $stock, $image);
        
        if ($stmt->execute()) {
            $produit_id = $this->conn->insert_id;
            
            // Ajouter les catégories
            if (!empty($categories)) {
                $this->addCategories($produit_id, $categories);
            }
            
            return $produit_id;
        }
        return false;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY date_creation DESC";
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

    public function getByCategorie($categorie_id) {
        $query = "SELECT p.* FROM " . $this->table . " p 
                  INNER JOIN produit_categorie pc ON p.id = pc.produit_id 
                  WHERE pc.categorie_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $categorie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $nom, $description, $prix, $stock, $image = null, $categories = []) {
        if ($image) {
            $query = "UPDATE " . $this->table . " SET nom = ?, description = ?, prix = ?, stock = ?, image = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssdisi", $nom, $description, $prix, $stock, $image, $id);
        } else {
            $query = "UPDATE " . $this->table . " SET nom = ?, description = ?, prix = ?, stock = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssdii", $nom, $description, $prix, $stock, $id);
        }
        
        if ($stmt->execute()) {
            // Mettre à jour les catégories
            if (!empty($categories)) {
                $this->removeAllCategories($id);
                $this->addCategories($id, $categories);
            }
            return true;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }

    public function addCategories($produit_id, $categories) {
        $query = "INSERT INTO produit_categorie (produit_id, categorie_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        
        foreach ($categories as $categorie_id) {
            $stmt->bind_param("ii", $produit_id, $categorie_id);
            $stmt->execute();
        }
    }

    public function removeAllCategories($produit_id) {
        $query = "DELETE FROM produit_categorie WHERE produit_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $produit_id);
        return $stmt->execute();
    }

    public function getCategories($produit_id) {
        $query = "SELECT c.* FROM categories c 
                  INNER JOIN produit_categorie pc ON c.id = pc.categorie_id 
                  WHERE pc.produit_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $produit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStock($id, $quantite) {
        $query = "UPDATE " . $this->table . " SET stock = stock - ? WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantite, $id);
        
        return $stmt->execute();
    }
}
?>