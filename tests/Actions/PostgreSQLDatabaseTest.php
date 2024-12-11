<?php

namespace Tests\Actions;

use PHPUnit\Framework\TestCase;
use App\Actions\PostgreSQLDatabase;

class PostgreSQLDatabaseTest extends TestCase {
    public function testDatabaseConnection(): void {
        $database = new PostgreSQLDatabase();
        $database->connect();

        $this->assertInstanceOf(\PDO::class, $database->pdo);
    }
}
