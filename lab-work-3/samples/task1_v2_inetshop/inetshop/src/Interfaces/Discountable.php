<?php
namespace App\Interfaces;

interface Discountable {
    public function applyDiscount($percent): void;
}
