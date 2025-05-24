-- Insert sample users
INSERT INTO users (username, email, password_hash, full_name, is_admin) VALUES
('admin', 'admin@baygorn.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', TRUE),
('user1', 'user1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User One', FALSE),
('user2', 'user2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User Two', FALSE);

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('Action', 'Các game hành động với lối chơi gay cấn'),
('Adventure', 'Game phiêu lưu với cốt truyện hấp dẫn'),
('RPG', 'Game nhập vai với hệ thống phát triển nhân vật'),
('Strategy', 'Game chiến thuật đòi hỏi tư duy'),
('Simulation', 'Game mô phỏng thế giới thực'),
('Survival', 'Game sinh tồn'),
('FPS', 'Game bắn súng góc nhìn thứ nhất'),
('Sandbox', 'Game thế giới mở sáng tạo');

-- Insert sample games (giá VND)
INSERT INTO games (title, description, detail_desc, platform, price, stock, image_url, modified_by, status, meta) VALUES
('Minecraft', 'Game thế giới mở với đồ họa khối', 'Khám phá thế giới vô tận, xây dựng và sáng tạo trong một thế giới được tạo ra từ các khối. Sinh tồn qua những đêm đầy quái vật hoặc xây dựng những công trình vĩ đại trong chế độ sáng tạo.', 'PC, Mobile, Console', 720000, 100, 'minecraft.jpg', 1, 'active', '{"genre": "Sandbox", "publisher": "Mojang", "release_date": "2011-11-18"}'),
('Roblox', 'Nền tảng game trực tuyến và hệ thống tạo game', 'Roblox là nền tảng cho phép người chơi tham gia và tạo ra vô số trò chơi khác nhau. Với cộng đồng sáng tạo lớn, Roblox cung cấp trải nghiệm game đa dạng và phong phú.', 'PC, Mobile, Console', 0, 999, 'game_roblox.jpg', 1, 'active', '{"genre": "Platform", "publisher": "Roblox Corporation", "release_date": "2006-09-01"}'),
('Palworld', 'Game sinh tồn thế giới mở với các sinh vật Pal', 'Khám phá thế giới kỳ diệu với những sinh vật gọi là "Pal". Thu thập, huấn luyện và chiến đấu cùng Pal trong hành trình sinh tồn đầy thú vị. Xây dựng căn cứ và phát triển đội hình Pal của riêng bạn.', 'PC', 1440000, 50, 'game_palworld.jpeg', 1, 'active', '{"genre": "Survival", "publisher": "Pocketpair", "release_date": "2024-01-19"}'),
('Team Fortress', 'Game bắn súng đội nhóm hành động', 'Game bắn súng góc nhìn thứ nhất với 9 class nhân vật độc đáo. Mỗi class có những kỹ năng và vai trò riêng biệt, tạo nên những trận chiến chiến thuật gay cấn.', 'PC', 480000, 75, 'game_tf.jpg', 1, 'active', '{"genre": "FPS", "publisher": "Valve", "release_date": "2007-10-10"}'),
('I Am Legion', 'Game chiến thuật ma quỷ', 'Trở thành chúa tể bóng tối trong game chiến thuật độc đáo. Chỉ huy đội quân quỷ dữ và mở rộng lãnh địa của bạn trong thế giới đen tối.', 'PC', 600000, 60, 'game_i_am_legion.jpg', 1, 'active', '{"genre": "Strategy", "publisher": "Dark Games", "release_date": "2024-02-01"}'),
('Brain Rot Evolution', 'Game sinh tồn zombie độc đáo', 'Sinh tồn trong thế giới hậu tận thế với những zombie tiến hóa. Kết hợp yếu tố sinh tồn và chiến thuật, người chơi phải thích nghi và phát triển để tồn tại.', 'PC', 384000, 80, 'brainrotevolution.webp', 1, 'active', '{"genre": "Survival", "publisher": "Zombie Studios", "release_date": "2024-01-15"}');

-- Insert game categories
INSERT INTO game_categories (game_id, category_id, modified_by) VALUES
(1, 2, 1), -- Minecraft - Adventure
(1, 8, 1), -- Minecraft - Sandbox
(1, 6, 1), -- Minecraft - Survival
(2, 1, 1), -- Roblox - Action
(2, 8, 1), -- Roblox - Sandbox
(3, 6, 1), -- Palworld - Survival
(3, 3, 1), -- Palworld - RPG
(3, 2, 1), -- Palworld - Adventure
(4, 1, 1), -- Team Fortress - Action
(4, 7, 1), -- Team Fortress - FPS
(5, 4, 1), -- I Am Legion - Strategy
(5, 3, 1), -- I Am Legion - RPG
(6, 6, 1), -- Brain Rot Evolution - Survival
(6, 1, 1); -- Brain Rot Evolution - Action

-- Insert sample news
INSERT INTO news (title, content, summary, image_url, status, published_at, modified_by) VALUES
('Minecraft 1.21: Cuộc Cách Mạng Mới', 
'Minecraft 1.21 sẽ mang đến những thay đổi đột phá với các block mới, cơ chế craft mới và nhiều tính năng thú vị. Phiên bản cập nhật này hứa hẹn sẽ làm mới trải nghiệm game của người chơi với những công cụ sáng tạo mới và các thử thách mới...', 
'Cập nhật lớn nhất của Minecraft trong năm 2024', 
'minecraftupdate.jpg', 'published', NOW(), 1),

('Team Fortress 2: Bản Cập Nhật Mùa Đông 2024', 
'Valve vừa tung ra bản cập nhật mới cho Team Fortress 2 với nhiều vũ khí mới, bản đồ mới và cân bằng lại các class. Bản cập nhật này cũng giới thiệu chế độ chơi mới và các cosmetic items theo chủ đề mùa đông...', 
'Bản cập nhật lớn với nhiều tính năng mới', 
'tftupdate.avif', 'published', NOW(), 1),

('Palworld: Hiện Tượng Game Mới', 
'Palworld đã phá vỡ kỷ lục với hơn 2 triệu người chơi đồng thời trên Steam. Game kết hợp giữa sinh tồn và thu thập monster đã tạo nên cơn sốt trong cộng đồng game thủ. Nhà phát triển hứa hẹn sẽ tiếp tục cập nhật thêm nhiều nội dung mới...', 
'Game hiện tượng đầu năm 2024', 
'game_palworld.jpeg', 'published', NOW(), 1);

-- Insert sample carts
INSERT INTO carts (user_id) VALUES (2), (3);

-- Insert sample cart items
INSERT INTO cart_items (cart_id, game_id, quantity) VALUES
(1, 1, 1), -- User2's cart - Minecraft
(1, 3, 1), -- User2's cart - Palworld
(2, 4, 1); -- User3's cart - Team Fortress

-- Insert sample orders (giá VND)
INSERT INTO orders (user_id, status, total_amount, payment_method, payment_status) VALUES
(2, 'completed', 2160000, 'credit_card', 'paid'),
(3, 'completed', 480000, 'paypal', 'paid'),
(2, 'pending', 1440000, 'credit_card', 'pending');

-- Insert sample order details (giá VND)
INSERT INTO order_details (order_id, game_id, unit_price, quantity) VALUES
(1, 1, 720000, 1), -- Order 1 - Minecraft
(1, 3, 1440000, 1), -- Order 1 - Palworld
(2, 4, 480000, 1), -- Order 2 - Team Fortress
(3, 3, 1440000, 1); -- Order 3 - Palworld

-- Insert sample transactions (giá VND)
INSERT INTO transactions (order_id, user_id, amount, payment_method, payment_status, transaction_code, payment_details) VALUES
(1, 2, 2160000, 'credit_card', 'completed', 'TRX-001', '{"card_last4": "4242", "card_brand": "visa"}'),
(2, 3, 480000, 'paypal', 'completed', 'TRX-002', '{"paypal_email": "user2@example.com"}'),
(3, 2, 1440000, 'credit_card', 'pending', 'TRX-003', '{"card_last4": "4242", "card_brand": "visa"}');

-- Insert sample history (giá VND)
INSERT INTO history (user_id, game_id, order_id, quantity, price) VALUES
(2, 1, 1, 1, 720000), -- User2 bought Minecraft
(2, 3, 1, 1, 1440000), -- User2 bought Palworld
(3, 4, 2, 1, 480000); -- User3 bought Team Fortress