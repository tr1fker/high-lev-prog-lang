<?php
// -------------------------
// Интерфейс для скидок
// -------------------------
interface Discountable {
    public function applyDiscount($percent);
}

// -------------------------
// Трейт для логирования
// -------------------------
trait Loggable {
    public function log($message) {
        echo "<br>"."[LOG] " . $message . "<br>";
    }
}

// -------------------------
// Родительский класс Product
// -------------------------
class Product implements Discountable {
    protected $name;
    protected $price;

    public function __construct($name, $price) {
        $this->name  = $name;
        $this->price = $price;
    }

    public function getInfo() {
        return "{$this->name} — {$this->price} руб.";
    }

    // Реализация метода интерфейса
    public function applyDiscount($percent) {
        $discount = $this->price * ($percent / 100);
        $this->price -= $discount;
    }
}

// -------------------------
// Дочерний класс Book
// -------------------------
class Book extends Product {
    private $author;

    public function __construct($name, $price, $author) {
        parent::__construct($name, $price);
        $this->author = $author;
    }

    public function getInfo() {
        return "Книга: {$this->name} (Автор: {$this->author}) — {$this->price} руб.";
    }
}

// -------------------------
// Дочерний класс Electronic
// -------------------------
class Electronic extends Product {
    use Loggable; // Подключаем трейт

    private $brand;

    public function __construct($name, $price, $brand) {
        parent::__construct($name, $price);
        $this->brand = $brand;
    }

    public function getInfo() {
        return "Электроника: {$this->name} (Бренд: {$this->brand}) — {$this->price} руб.";
    }

    // Переопределяем метод скидки, добавляем логирование
    public function applyDiscount($percent) {
        parent::applyDiscount($percent);
        $this->log("Скидка {$percent}% применена к {$this->name}");
    }
}

// -------------------------
// Тестовый сценарий
// -------------------------
$book = new Book("PHP для начинающих", 60, "Иван Иванов");
$phone = new Electronic("Смартфон Honor 200", 1500, "TechBrand");

echo $book->getInfo() . "<br>";
echo $phone->getInfo() . "<br><br>";

// Применяем скидки
$book->applyDiscount(10);
$phone->applyDiscount(20);

echo $book->getInfo() . "<br>";
echo $phone->getInfo() . "<br>";
