<?php

namespace App\Actions;

use App\Interfaces\DatabaseInterface;
use App\Interfaces\SearchInterface;
use PDO;

class AuthorSearch implements SearchInterface {
    private $database;

    public function __construct(DatabaseInterface $database) {
        $this->database = $database;
    }

    // In AuthorSearch.php
    public function searchAuthors(string $authorName): array {
        $this->database->connect();
        $pdo = $this->database->pdo; // assuming pdo is a public property
        $stmt = $pdo->prepare("
            SELECT a.name AS author, COALESCE(b.title, '<none>') AS book
            FROM authors a
            LEFT JOIN books b ON a.id = b.author_id
            WHERE a.name ILIKE :author
            ORDER BY a.name, b.title
        ");
        $stmt->execute([':author' => '%' . $authorName . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
