CREATE DATABASE leadFlow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE leadFlow;

CREATE TABLE users (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    role ENUM('User', 'Admin') DEFAULT 'User'
) ENGINE=InnoDB;

CREATE TABLE leads (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) NOT NULL,
    company VARCHAR(128) NOT NULL,
    email VARCHAR(128),
    phone VARCHAR(32),
    source VARCHAR(128),
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('New', 'Contacted', 'Closed', 'Lost') DEFAULT 'New',
    
    user_id INT UNSIGNED NOT NULL,
    
    CONSTRAINT fk_user FOREIGN KEY(user_id) REFERENCES users(id) ON  DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX index_name ON leads(name);
CREATE INDEX index_company ON leads(company);
CREATE INDEX index_created_at ON leads(created_at);
CREATE INDEX index_updated_at ON leads(updated_at);
CREATE INDEX index_status ON leads(status);
CREATE INDEX index_user ON leads(user_id);