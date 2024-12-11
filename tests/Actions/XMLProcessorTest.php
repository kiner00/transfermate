<?php

namespace Tests\Actions;

use PHPUnit\Framework\TestCase;
use App\Actions\XMLProcessor;
use App\Interfaces\DatabaseInterface;

class XMLProcessorTest extends TestCase {
    private $databaseMock;
    private $xmlProcessor;

    protected function setUp(): void {
        // Mock the DatabaseInterface
        $this->databaseMock = $this->createMock(DatabaseInterface::class);

        // Instantiate the XMLProcessor with the mocked DatabaseInterface
        $this->xmlProcessor = new XMLProcessor($this->databaseMock);
    }

    public function testProcessFile(): void {
        $testFile = __DIR__ . '/fixtures/test.xml'; // Mock test XML file

        // Configure the database mock to handle expected calls
        $this->databaseMock->method('insertAuthor')->willReturn(1);
        $this->databaseMock->method('insertBook')->willReturn(null);

        // Test processing a file
        $this->xmlProcessor->processFile($testFile);

        // Assertions can be done on the number of interactions if required
        $this->addToAssertionCount(1); // No exception thrown
    }
}
