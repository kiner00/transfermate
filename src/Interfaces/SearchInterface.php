<?php

namespace App\Interfaces;

interface SearchInterface {
    public function searchAuthors(string $authorName): array;
}