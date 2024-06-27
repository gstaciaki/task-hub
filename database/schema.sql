SET foreign_key_checks = 0;

DROP TABLE IF EXISTS tasks;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT NOW(),
    finished_at DATETIME,
    priority enum('urgent','high','normal','low'),
    status enum('open', 'closed') DEFAULT 'open'
);

DROP TABLE IF EXISTS task_comments;

CREATE TABLE task_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id int NOT NULL REFERENCES tasks(id) ON DELETE CASCADE,
    description text NOT NULL
);

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    encrypted_password VARCHAR(255) NOT NULL,
    is_admin VARCHAR(5) DEFAULT 'false',
    created_at DATETIME DEFAULT NOW(),
    avatar_name VARCHAR (65)
);

DROP TABLE IF EXISTS task_user_owners;

CREATE TABLE task_user_owners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL REFERENCES tasks(id) ON DELETE RESTRICT,
    user_id INT NOT NULL REFERENCES tasks(id) ON DELETE RESTRICT
);

SET foreign_key_checks = 1;