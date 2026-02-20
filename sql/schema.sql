CREATE DATABASE IF NOT EXISTS crochet_art_by_orly;
USE crochet_art_by_orly;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    gender ENUM('Femme', 'Homme', 'Enfant') NOT NULL
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),
    stock INT DEFAULT 0,
    -- Default/Available options (comma separated or separate tables, keeping it simple for now)
    sizes VARCHAR(255), -- e.g. "S,M,L,XL"
    colors VARCHAR(255), -- e.g. "Rouge,Bleu,Vert"
    lengths VARCHAR(255), -- e.g. "Courte,Longue"
    wool_types VARCHAR(255), -- e.g. "Coton,Velours,Acrylique"
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Paid', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    payment_method VARCHAR(50),
    payment_reference VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order Items table (with customization)
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    selected_size VARCHAR(20),
    selected_color VARCHAR(30),
    selected_length VARCHAR(20),
    selected_wool_type VARCHAR(50),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Insert categories
INSERT INTO categories (name, gender) VALUES
('Top', 'Femme'), ('Robe', 'Femme'), ('Bikini', 'Femme'), ('Ensemble plage', 'Femme'),
('Chapeau', 'Femme'), ('Sac', 'Femme'), ('Jupe', 'Femme'), ('Culotte', 'Femme'),
('Chemise', 'Homme'), ('Ensemble', 'Homme'), ('Vêtement de plage', 'Homme'),
('Peluche', 'Enfant'), ('Vêtements unisexes', 'Enfant'), ('Chaussettes', 'Enfant');
