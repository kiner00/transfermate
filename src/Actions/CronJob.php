<?php

use App\Actions\AuthorSearch;
use App\Actions\PostgreSQLDatabase;
use App\Actions\XMLProcessor;

require_once __DIR__ . '/vendor/autoload.php'; // Ensure autoloading for dependencies

try {
    // Dependency injection
    $database = new PostgreSQLDatabase();
    $database->connect();

    $xmlProcessor = new XMLProcessor($database);
    $search = new AuthorSearch($database);

    // Process XML files
    $directoryPath = getenv('XML_DIRECTORY') ?: '/path/to/start/folder';
    $xmlProcessor->processDirectory($directoryPath);

    // Handle search
    if (isset($_GET['author'])) {
        $authorName = filter_input(INPUT_GET, 'author', FILTER_SANITIZE_STRING);
        if ($authorName) {
            $results = $search->searchAuthors($authorName);
            header('Content-Type: application/json');
            echo json_encode($results);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid author name']);
        }
    }
} catch (Exception $e) {
    // Log the error
    error_log($e->getMessage());

    // Send a generic error response
    http_response_code(500);
    echo json_encode(['error' => 'An unexpected error occurred. Please try again later.']);
}
