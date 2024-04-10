CREATE DATABASE IF NOT EXISTS cf2h;

CREATE USER 'cf2h'@'localhost' IDENTIFIED BY 'cf2hPass';
GRANT ALL PRIVILEGES ON cf2h.* TO 'cf2h'@'localhost';
FLUSH PRIVILEGES;

USE cf2h;

-- Create the user table
CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(255) NOT NULL,
    password VARCHAR(128) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'visitor', 'unassigned') DEFAULT 'unassigned',
    PRIMARY KEY (username)
);

-- Insert a default admin user
INSERT INTO users (username, password, first_name, last_name, role)
    VALUES ('admin', 'changeme', 'Admin', 'User', 'admin');