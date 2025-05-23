 CREATE DATABASE IF NOT EXISTS secure_coding;

USE secure_coding;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    password VARCHAR(50)
);

INSERT INTO users (username, password) VALUES 
('admin', 'admin123'),
('user1', 'pass1');

