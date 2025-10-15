<?php
namespace App\Models;

use App\Traits\Loggable;

class Electronic extends Product {
    use Loggable;

    private string $brand;
    private int $warrantyMonths;

    public function __construct(string $name, float $price, string $brand, int $warrantyMonths = 12) {
        parent::__construct($name, $price);
        $this->brand = $brand;
        $this->warrantyMonths = $warrantyMonths;
    }

    public function getBrand(): string { return $this->brand; }
    public function getWarranty(): int { return $this->warrantyMonths; }

    public function applyDiscount($percent): void {
        parent::applyDiscount($percent);
        $this->log("Скидка {$percent}% применена к {$this->name}");
    }

    public function getInfo(): string {
        return "Электроника: {$this->name} (Бренд: {$this->brand}, гарантия {$this->warrantyMonths} мес.) — "
            . number_format($this->price, 2, '.', ' ') . " руб.";
    }
}
