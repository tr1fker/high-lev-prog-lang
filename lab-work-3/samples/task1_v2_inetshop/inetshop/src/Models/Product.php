<?php
namespace App\Models;

use App\Interfaces\Discountable;

abstract class Product implements Discountable {
    protected string $name;
    protected float $price;
    protected string $id;

    public function __construct(string $name, float $price) {
        $this->id = uniqid("p_", true);
        $this->name = $name;
        $this->price = $price;
    }

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }

    public function applyDiscount($percent): void {
        $percent = max(0, min(100, (float)$percent));
        $this->price -= $this->price * ($percent / 100);
    }

    abstract public function getInfo(): string;
}
