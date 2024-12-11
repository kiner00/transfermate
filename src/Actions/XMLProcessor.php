<?php

namespace App\Actions;

use App\Interfaces\DatabaseInterface;
use App\Interfaces\XMLProcessorInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class XMLProcessor implements XMLProcessorInterface {
    private $database;

    public function __construct(DatabaseInterface $database) {
        $this->database = $database;
    }

    public function processDirectory(string $directory): void {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'xml') {
                $this->processFile($file->getPathname());
            }
        }
    }

    public function processFile(string $filePath): void {
        $xml = simplexml_load_file($filePath);
        foreach ($xml->book as $book) {
            $authorName = (string)$book->author;
            $bookTitle = (string)$book->name;

            $authorId = $this->database->insertAuthor($authorName);
            $this->database->insertOrUpdateBook($authorId, $bookTitle);
        }
    }
}
