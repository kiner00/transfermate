<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Actions\AuthorSearch;
use App\Interfaces\DatabaseInterface;
use PDO;
use PDOStatement;

class AuthorSearchTest extends TestCase {
    private $databaseMock;
    private $pdoMock;
    private $stmtMock;
    private $authorSearch;

    protected function setUp(): void {
        // Create a mock of the DatabaseInterface
        $this->databaseMock = $this->createMock(DatabaseInterface::class);

        // Create a mock of the PDO object
        $this->pdoMock = $this->createMock(PDO::class);

        // Create a mock of the PDOStatement object
        $this->stmtMock = $this->createMock(PDOStatement::class);

        // Configure the DatabaseInterface mock to return the PDO mock
        $this->databaseMock->method('connect')->willReturn(null);
        $this->databaseMock->pdo = $this->pdoMock;

        // Instantiate the AuthorSearch class with the mocked DatabaseInterface
        $this->authorSearch = new AuthorSearch($this->databaseMock);
    }

    public function testSearchAuthorsReturnsResults(): void {
        // Define the expected query result
        $expectedResults = [
            ['author' => 'John Doe', 'book' => 'Book 1'],
            ['author' => 'John Doe', 'book' => 'Book 2'],
        ];

        // Configure the PDOStatement mock to return the expected results
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetchAll')->willReturn($expectedResults);

        // Configure the PDO mock to return the PDOStatement mock
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);

        // Call the searchAuthors method
        $results = $this->authorSearch->searchAuthors('John');

        // Assert the results match the expected output
        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertEquals($expectedResults, $results);
    }

    public function testSearchAuthorsReturnsNoResults(): void {
        // Define the expected query result (empty array)
        $expectedResults = [];

        // Configure the PDOStatement mock to return the expected results
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetchAll')->willReturn($expectedResults);

        // Configure the PDO mock to return the PDOStatement mock
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);

        // Call the searchAuthors method
        $results = $this->authorSearch->searchAuthors('Nonexistent Author');

        // Assert the results are an empty array
        $this->assertIsArray($results);
        $this->assertCount(0, $results);
        $this->assertEquals($expectedResults, $results);
    }

    public function testSearchAuthorsHandlesException(): void {
        // Configure the PDOStatement mock to throw an exception
        $this->stmtMock->method('execute')->willThrowException(new \Exception('Database Error'));

        // Configure the PDO mock to return the PDOStatement mock
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);

        // Expect an exception when calling searchAuthors
        $this->expectException(\Exception::class);

        // Call the searchAuthors method
        $this->authorSearch->searchAuthors('Error Author');
    }
}
