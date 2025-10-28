-- Create database (run once if needed)
-- CREATE DATABASE mini_amazon CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE mini_amazon;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(191) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) UNIQUE
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name VARCHAR(200) NOT NULL,
  slug VARCHAR(220) UNIQUE,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS addresses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  line1 VARCHAR(200) NOT NULL,
  line2 VARCHAR(200) NULL,
  city VARCHAR(100) NOT NULL,
  state VARCHAR(100) NOT NULL,
  zip VARCHAR(20) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  address_id INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  status ENUM('placed','paid','shipped','delivered','cancelled') NOT NULL DEFAULT 'placed',
  payment_method ENUM('cod','razorpay') NOT NULL DEFAULT 'cod',
  payment_ref VARCHAR(191) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (address_id) REFERENCES addresses(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  qty INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Seed: admin user (password: admin123)
INSERT INTO users (name,email,password_hash,role) VALUES
('Admin','admin@example.com','$2y$10$qQKDAZfYPQdCEV0CqbwHtO7G7dQWfq/3GpyGvJk6m7m7P3D8Y0G.S','admin')
ON DUPLICATE KEY UPDATE email=email;

-- Seed: categories
INSERT INTO categories (name,slug) VALUES
('Mobiles','mobiles'),
('Laptops','laptops'),
('Accessories','accessories')
ON DUPLICATE KEY UPDATE slug=VALUES(slug);

-- Seed: products (using placeholder image)
INSERT INTO products (category_id,name,slug,description,price,stock,image) VALUES
(1,'Nova X1 5G 128GB','nova-x1-128','Powerful 5G phone with 50MP camera.',15999,25,'assets/img/placeholder.svg'),
(1,'Kite A2 8GB/256GB','kite-a2-256','Smooth performance with AMOLED display.',21999,30,'assets/img/placeholder.svg'),
(2,'LiteBook 14 Ryzen 5','litebook-14-r5','Slim, fast, all‑day battery.',45999,15,'assets/img/placeholder.svg'),
(2,'ProNote 15 i5','pronote-15-i5','Great for work & study.',54999,12,'assets/img/placeholder.svg'),
(3,'Type‑C Fast Charger','typec-charger','18W fast charging adapter.',699,200,'assets/img/placeholder.svg'),
(3,'Wireless Earbuds','wireless-buds','Crystal clear calls and bass.',1999,90,'assets/img/placeholder.svg')
ON DUPLICATE KEY UPDATE slug=VALUES(slug);
