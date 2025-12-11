-- Création de la base de données
CREATE DATABASE IF NOT EXISTS ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce_db;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    is_admin TINYINT(1) DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE IF NOT EXISTS produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table de liaison produits-catégories (relation many-to-many)
CREATE TABLE IF NOT EXISTS produit_categorie (
    produit_id INT,
    categorie_id INT,
    PRIMARY KEY (produit_id, categorie_id),
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Table des commandes
CREATE TABLE IF NOT EXISTS commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    montant_total DECIMAL(10, 2) NOT NULL,
    statut VARCHAR(50) DEFAULT 'en_attente',
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des détails de commande
CREATE TABLE IF NOT EXISTS commande_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);

-- Insertion d'un admin par défaut (mot de passe: admin123)
INSERT INTO users (nom, prenom, email, password, is_admin) VALUES 
('Admin', 'Super', 'admin@ecommerce.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Insertion de catégories de test
INSERT INTO categories (nom, description) VALUES 
('Électronique', 'Appareils électroniques et high-tech'),
('Vêtements', 'Vêtements pour homme et femme'),
('Livres', 'Livres et magazines'),
('Maison', 'Articles pour la maison');

-- Insertion de produits de test
INSERT INTO produits (nom, description, prix, stock, image) VALUES 
('Smartphone XY', 'Smartphone dernière génération', 599.99, 50, 'smartphone.jpg'),
('Laptop Pro', 'Ordinateur portable haute performance', 1299.99, 30, 'laptop.jpg'),
('T-Shirt Classic', 'T-shirt en coton bio', 29.99, 100, 'tshirt.jpg'),
('Jean Slim', 'Jean slim fit confortable', 79.99, 75, 'jean.jpg'),
('Livre PHP', 'Guide complet du développement PHP', 39.99, 40, 'livre.jpg'),
('Lampe LED', 'Lampe de bureau LED moderne', 49.99, 60, 'lampe.jpg');

-- Liaison produits-catégories
INSERT INTO produit_categorie (produit_id, categorie_id) VALUES 
(1, 1), (2, 1), (3, 2), (4, 2), (5, 3), (6, 4), (1, 4);