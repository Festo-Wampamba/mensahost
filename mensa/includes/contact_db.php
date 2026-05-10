<?php
require_once __DIR__ . '/../db.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(120)  NOT NULL,
    email      VARCHAR(254)  NOT NULL,
    subject    VARCHAR(255)  NOT NULL,
    message    TEXT          NOT NULL,
    status     ENUM('unread','read','replied') NOT NULL DEFAULT 'unread',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
