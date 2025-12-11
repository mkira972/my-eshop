<?php
require_once '../config/database.php';

class Commande {
    private $conn;
    private $table = "commandes";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($user_id, $panier) {
        // Calculer le montant total
        $montant_total = 0;
        foreach ($panier as $item) {
            $montant_total += $item['prix'] * $item['quantite'];
        }

        // Créer la commande
        $query = "INSERT INTO " . $this->table . " (user_id, montant_total) VALUES (?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("id", $user_id, $montant_total);
        
        if ($stmt->execute()) {
            $commande_id = $this->conn->insert_id;
            
            // Ajouter les détails de commande
            $this->addDetails($commande_id, $panier);
            
            return $commande_id;
        }
        return false;
    }

    private function addDetails($commande_id, $panier) {
        $query = "INSERT INTO commande_details (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($panier as $produit_id => $item) {
            $stmt->bind_param("iiid", $commande_id, $produit_id, $item['quantite'], $item['prix']);
            $stmt->execute();
        }
    }

    public function getAll() {
        $query = "SELECT c.*, u.nom, u.prenom, u.email 
                  FROM " . $this->table . " c 
                  INNER JOIN users u ON c.user_id = u.id 
                  ORDER BY c.date_commande DESC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getByUser($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = ? ORDER BY date_commande DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT c.*, u.nom, u.prenom, u.email, u.telephone, u.adresse 
                  FROM " . $this->table . " c 
                  INNER JOIN users u ON c.user_id = u.id 
                  WHERE c.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    public function getDetails($commande_id) {
        $query = "SELECT cd.*, p.nom, p.image 
                  FROM commande_details cd 
                  INNER JOIN produits p ON cd.produit_id = p.id 
                  WHERE cd.commande_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $commande_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStatut($id, $statut) {
        $query = "UPDATE " . $this->table . " SET statut = ? WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $statut, $id);
        
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