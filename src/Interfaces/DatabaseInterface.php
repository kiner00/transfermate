<?php

namespace App\Interfaces;

use PDO;

interface DatabaseInterface {
    public function connect();
    public function insertAuthor(string $name): int;
    public function insertOrUpdateBook(int $authorId, string $title): void;
    public function getPdo(): PDO;
}