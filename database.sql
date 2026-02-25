CREATE DATABASE leadFlow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE leadFlow;

CREATE TABLE users (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('User', 'Admin') DEFAULT 'User',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE leads (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) NOT NULL,
    company VARCHAR(128) NOT NULL,
    email VARCHAR(128) UNIQUE,
    phone VARCHAR(32),
    source VARCHAR(128),
    status ENUM('New', 'Contacted', 'Closed', 'Lost') DEFAULT 'New',
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT UNSIGNED NOT NULL,
    
    CONSTRAINT fk_user FOREIGN KEY(user_id) REFERENCES users(id) ON  DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_email ON leads(email);
CREATE INDEX idx_status ON leads(status);
CREATE INDEX idx_user ON leads(user_id);