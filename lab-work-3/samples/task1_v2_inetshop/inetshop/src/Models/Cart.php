<?php
namespace App\Models;

class Cart {
    /** @var Product[] */
    private array $items = [];

    public function add(Product $product): void {
        $this->items[$product->getId()] = $product;
    }

    public function remove(string $productId): void {
        unset($this->items[$productId]);
    }

    public function getItems(): array {
        return array_values($this->items);
    }

    public function total(): float {
        return array_reduce($this->items, fn($sum, $p) => $sum + $p->getPrice(), 0.0);
    }

    public function clear(): void {
        $this->items = [];
    }
}
