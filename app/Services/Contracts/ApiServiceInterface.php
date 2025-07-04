<?php
namespace App\Services\Contracts;

interface ApiServiceInterface
{
    /**
     * Fetch articles from the API and return as an array.
     *
     * @return array
     */
    public function fetchArticles();
} 