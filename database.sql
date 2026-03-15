-- Restaurant Management Database Schema
-- Database: restromanage

-- ==========================================
-- TABLE: users (for admin/staff login)
-- ==========================================
CREATE TABLE IF NOT EXISTS users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(15),
  image VARCHAR(255),
  role ENUM('admin', 'kitchen', 'staff', 'user') DEFAULT 'user',
  status ENUM('active', 'inactive') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_role (role)
);

-- ==========================================
-- TABLE: staff (for kitchen staff management)
-- ==========================================
CREATE TABLE IF NOT EXISTS staff (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  position VARCHAR(100) NOT NULL,
  image VARCHAR(255),
  phone VARCHAR(15),
  email VARCHAR(100),
  status ENUM('present', 'absent', 'leave', 'break') DEFAULT 'absent',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status)
);

-- ==========================================
-- TABLE: menu
-- ==========================================
CREATE TABLE IF NOT EXISTS menu (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(150) NOT NULL,
  category VARCHAR(100) NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  image VARCHAR(255),
  description TEXT,
  is_available BOOLEAN DEFAULT 1,
  created_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_category (category),
  INDEX idx_available (is_available)
);

-- ==========================================
-- TABLE: orders
-- ==========================================
CREATE TABLE IF NOT EXISTS orders (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  customer_name VARCHAR(100) NOT NULL,
  customer_phone VARCHAR(15),
  customer_email VARCHAR(100),
  total_price DECIMAL(10, 2) NOT NULL,
  status ENUM('pending', 'preparing', 'done', 'delivered', 'cancelled') DEFAULT 'pending',
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_status (status),
  INDEX idx_user_id (user_id),
  INDEX idx_created_at (created_at)
);

-- ==========================================
-- TABLE: order_details
-- ==========================================
CREATE TABLE IF NOT EXISTS order_details (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  menu_id INT NOT NULL,
  item_name VARCHAR(150) NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  unit_price DECIMAL(10, 2) NOT NULL,
  total_price DECIMAL(10, 2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (menu_id) REFERENCES menu(id) ON DELETE RESTRICT,
  INDEX idx_order_id (order_id)
);

-- ==========================================
-- SAMPLE DATA: Users (Admin + Staff)
-- Password for all: password
-- ==========================================
INSERT INTO users (name, email, password, phone, image, role, status) VALUES
('Admin User', 'admin@restro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 'https://i.pravatar.cc/150?img=0', 'admin', 'active'),
('John Doe', 'kitchen@restro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543211', 'https://i.pravatar.cc/150?img=1', 'kitchen', 'active'),
('Customer User', 'customer@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543212', 'https://i.pravatar.cc/150?img=2', 'user', 'active');

-- ==========================================
-- SAMPLE DATA: Staff
-- ==========================================
INSERT INTO staff (name, position, image, phone, email, status) VALUES
('Raj Kumar', 'Chef', 'https://i.pravatar.cc/150?img=1', '9876543220', 'raj@restro.com', 'present'),
('Priya Singh', 'Sous Chef', 'https://i.pravatar.cc/150?img=2', '9876543221', 'priya@restro.com', 'present'),
('Amit Patel', 'Line Cook', 'https://i.pravatar.cc/150?img=3', '9876543222', 'amit@restro.com', 'absent'),
('Neha Sharma', 'Pastry Chef', 'https://i.pravatar.cc/150?img=4', '9876543223', 'neha@restro.com', 'leave'),
('Vikram Joshi', 'Dishwasher', 'https://i.pravatar.cc/150?img=5', '9876543224', 'vikram@restro.com', 'break');

-- ==========================================
-- SAMPLE DATA: Menu
-- ==========================================
INSERT INTO menu (name, category, price, image, description, is_available, created_by) VALUES
('Paneer Butter Masala', 'Veg', 250, 'https://images.unsplash.com/photo-1604908177522-4290f3a1c7fa', 'Soft paneer in creamy tomato sauce', 1, 1),
('Chicken Biryani', 'Non-Veg', 350, 'https://images.unsplash.com/photo-1600628422019-7c7f8e0a6b28', 'Fragrant rice with spiced chicken', 1, 1),
('Veg Fried Rice', 'Chinese', 180, 'https://images.unsplash.com/photo-1603133872878-684f208fb84b', 'Mixed vegetables with rice', 1, 1),
('Manchurian', 'Chinese', 200, 'https://images.unsplash.com/photo-1628294895950-9805252327bc', 'Crispy balls in tangy sauce', 1, 1),
('Butter Naan', 'Veg', 40, 'https://images.unsplash.com/photo-1626074353765-517a681e40be', 'Soft Indian bread with butter', 1, 1),
('Cold Drink', 'Drinks', 60, 'https://images.unsplash.com/photo-1580910051074-7e31a4b8f1bb', 'Refreshing cold beverage', 1, 1);

-- ==========================================
-- SAMPLE DATA: Orders
-- ==========================================
INSERT INTO orders (user_id, customer_name, customer_phone, customer_email, total_price, status, notes) VALUES
(3, 'Alice Johnson', '9999999990', 'alice@example.com', 500, 'pending', 'No onions'),
(3, 'Bob Smith', '9999999991', 'bob@example.com', 350, 'preparing', 'Extra spicy'),
(3, 'Charlie Brown', '9999999992', 'charlie@example.com', 120, 'done', 'For delivery');

-- ==========================================
-- SAMPLE DATA: Order Details
-- ==========================================
INSERT INTO order_details (order_id, menu_id, item_name, quantity, unit_price, total_price) VALUES
(1, 1, 'Paneer Butter Masala', 2, 250, 500),
(2, 2, 'Chicken Biryani', 1, 350, 350),
(3, 5, 'Butter Naan', 3, 40, 120);

-- ==========================================
-- TABLE: kitchen_orders (per-item kitchen queue)
-- ==========================================
CREATE TABLE IF NOT EXISTS kitchen_orders (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  item_name VARCHAR(150) NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  status ENUM('pending','cooking','ready') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  INDEX idx_status (status),
  INDEX idx_order_id (order_id)
);

-- ==========================================
-- TABLE: sales_chart (order summaries for reporting)
-- ==========================================
CREATE TABLE IF NOT EXISTS sales_chart (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  order_date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  INDEX idx_order_date (order_date)
);
