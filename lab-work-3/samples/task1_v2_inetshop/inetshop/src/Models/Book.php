<?php
namespace App\Models;

class Book extends Product {
    private string $author;

    public function __construct(string $name, float $price, string $author) {
        parent::__construct($name, $price);
        $this->author = $author;
    }

    public function getAuthor(): string { return $this->author; }

    public function getInfo(): string {
        return "Книга: {$this->name} (Автор: {$this->author}) — " . number_format($this->price, 2, '.', ' ') . " руб.";
    }
}
