-- XÓA BẢNG NẾU ĐÃ TỒN TẠI (phải xóa theo thứ tự để tránh lỗi khóa ngoại)
IF OBJECT_ID('game_categories', 'U') IS NOT NULL DROP TABLE game_categories;
IF OBJECT_ID('categories', 'U') IS NOT NULL DROP TABLE categories;
IF OBJECT_ID('order_details', 'U') IS NOT NULL DROP TABLE order_details;
IF OBJECT_ID('orders', 'U') IS NOT NULL DROP TABLE orders;
IF OBJECT_ID('games', 'U') IS NOT NULL DROP TABLE games;
IF OBJECT_ID('users', 'U') IS NOT NULL DROP TABLE users;

-- 1. USERS
CREATE TABLE users (
    user_id INT PRIMARY KEY IDENTITY(1,1),
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    created_at DATETIME DEFAULT GETDATE()
);

-- 2. GAMES
CREATE TABLE games (
    game_id INT PRIMARY KEY IDENTITY(1,1),
    title VARCHAR(100) NOT NULL,
    description VARCHAR(MAX),
    genre VARCHAR(50),
    platform VARCHAR(50),
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    image_url VARCHAR(MAX),
    created_at DATETIME DEFAULT GETDATE()
);

-- 3. ORDERS
CREATE TABLE orders (
    order_id INT PRIMARY KEY IDENTITY(1,1),
    user_id INT NOT NULL,
    order_date DATETIME DEFAULT GETDATE(),
    status VARCHAR(20) DEFAULT 'pending',
    total_amount DECIMAL(10, 2),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- 4. ORDER_DETAILS
CREATE TABLE order_details (
    order_detail_id INT PRIMARY KEY IDENTITY(1,1),
    order_id INT NOT NULL,
    game_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    subtotal AS (quantity * unit_price) PERSISTED,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (game_id) REFERENCES games(game_id)
);

-- 5. CATEGORIES
CREATE TABLE categories (
    category_id INT PRIMARY KEY IDENTITY(1,1),
    name VARCHAR(50) UNIQUE NOT NULL
);

-- 6. GAME_CATEGORIES
CREATE TABLE game_categories (
    game_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (game_id, category_id),
    FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);
-- categories etc:
INSERT INTO categories (name) VALUES 
('Action'), ('Adventure'), ('RPG'), ('Strategy'), ('Sports'), ('Simulation');
