-- DROP TABLE nếu đã tồn tại (đúng thứ tự để không lỗi khóa ngoại)
IF OBJECT_ID('game_categories', 'U') IS NOT NULL DROP TABLE game_categories;
IF OBJECT_ID('order_details', 'U') IS NOT NULL DROP TABLE order_details;
IF OBJECT_ID('orders', 'U') IS NOT NULL DROP TABLE orders;
IF OBJECT_ID('categories', 'U') IS NOT NULL DROP TABLE categories;
IF OBJECT_ID('games', 'U') IS NOT NULL DROP TABLE games;
IF OBJECT_ID('users', 'U') IS NOT NULL DROP TABLE users;
CREATE DATABASE BayGorn1
-- USERS
CREATE TABLE users (
    user_id INT PRIMARY KEY IDENTITY(1,1),
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    created_at DATETIME DEFAULT GETDATE()
);

-- GAMES
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

-- ORDERS
CREATE TABLE orders (
    order_id INT PRIMARY KEY IDENTITY(1,1),
    user_id INT NOT NULL,
    order_date DATETIME DEFAULT GETDATE(),
    status VARCHAR(20) DEFAULT 'pending',
    total_amount DECIMAL(10, 2),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- ORDER_DETAILS
CREATE TABLE order_details (
    order_detail_id INT PRIMARY KEY IDENTITY(1,1),
    order_id INT NOT NULL,
    game_id INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (game_id) REFERENCES games(game_id)
);

-- CATEGORIES
CREATE TABLE game_categories (
    game_id INT NOT NULL,
    category_name VARCHAR(50) NOT NULL,
    PRIMARY KEY (game_id, category_name),
    FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE
);