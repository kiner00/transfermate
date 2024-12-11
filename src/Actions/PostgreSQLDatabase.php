<?php

namespace App\Actions;

use App\Interfaces\DatabaseInterface;
use PDO;

class PostgreSQLDatabase implements DatabaseInterface {
    private $pdo;

    public function connect() {
        $dbHost = 'localhost';
        $dbName = 'test_db';
        $dbUser = 'user';
        $dbPass = 'password';
        $this->pdo = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public function insertAuthor(string $name): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO authors (name) VALUES (:name)
            ON CONFLICT (name) DO UPDATE SET name = EXCLUDED.name
            RETURNING id
        ");
        $stmt->execute([':name' => $name]);
        return $stmt->fetchColumn();
    }

    public function insertOrUpdateBook(int $authorId, string $title): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO books (author_id, title)
            VALUES (:author_id, :title)
            ON CONFLICT (title) DO NOTHING
        ");
        $stmt->execute([':author_id' => $authorId, ':title' => $title]);
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
