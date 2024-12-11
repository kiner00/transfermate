<?php

namespace App\Interfaces;

interface XMLProcessorInterface {
    public function processFile(string $filePath): void;
    public function processDirectory(string $directory): void;
}