-- 1. Create the database
CREATE DATABASE IF NOT EXISTS formation_db;

-- 2. Use the database
USE formation_db;

-- 3. Create the users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE IF NOT EXISTS formations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    duration VARCHAR(100) NOT NULL,
    type VARCHAR(100) NOT NULL,
    reserved TINYINT(1) DEFAULT 0 -- 0 = not reserved, 1 = reserved
);


CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    formation_id INT NOT NULL,
    reserved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),  -- assuming you have a 'users' table
    FOREIGN KEY (formation_id) REFERENCES formations(id)
);

INSERT INTO formations (name, description, price, duration, type, reserved)
VALUES 
('Web Development Bootcamp', 'Learn HTML, CSS, JS, and backend technologies', 1200, '3 months', 'Technical', 0),

('Digital Marketing Essentials', 'Master SEO, SEM, and social media strategy', 800, '1.5 months', 'Business', 0),

('Graphic Design', 'Learn Adobe Photoshop, Illustrator, and design theory', 1000, '2 months', 'Creative', 0),

('Soft Skills Training', 'Improve communication, leadership, and teamwork', 500, '1 month', 'Soft Skills', 0),

('Data Analysis with Excel', 'Analyze and visualize data using Excel tools', 600, '3 weeks', 'Technical', 0);
