<?php
/**
 * Вариант 27: Финансовые инструменты
 * -----------------------------------
 * Это CLI‑скрипт (Command Line Interface), который запускается через терминал:
 *   php finance_cli.php
 *
 * Возможности:
 *   - Создание трёх активов: Акция, Облигация, Криптовалюта
 *   - Просмотр текущих цен и уровня риска
 *   - Изменение цены на заданный процент (simulateGrowth)
 *   - Выход из программы
 *
 * Всё реализовано в одном файле, без HTML и CSS.
 */

// ==========================
// 1. Базовый класс Asset
// ==========================
abstract class Asset {
    protected string $name;   // Название актива
    protected float $price;   // Текущая цена

    // Конструктор: принимает название и цену
    public function __construct(string $name, float $price) {
        $this->name = $name;
        $this->price = $price;
    }

    // Получить текущую цену
    public function getValue(): float {
        return $this->price;
    }

    // Обновить цену вручную
    public function updatePrice(float $newPrice): void {
        $this->price = $newPrice;
    }

    // Абстрактный метод: уровень риска (разный для каждого актива)
    abstract public function getRiskLevel(): string;

    // Смоделировать рост/падение на процент
    public function simulateGrowth(float $percent): void {
        $this->price += $this->price * ($percent / 100);
    }

    // Вернуть строку с информацией
    public function info(): string {
        return "{$this->name}: " . number_format($this->price, 2, '.', ' ') . " USD";
    }
}

// ==========================
// 2. Конкретные активы
// ==========================
class Stock extends Asset {
    public function getRiskLevel(): string {
        return "Средний риск (акции)";
    }
}

class Bond extends Asset {
    public function getRiskLevel(): string {
        return "Низкий риск (облигации)";
    }
}

class Cryptocurrency extends Asset {
    public function getRiskLevel(): string {
        return "Высокий риск (криптовалюты)";
    }
}

// ==========================
// 3. Инициализация активов
// ==========================
$assets = [
    "stock"  => new Stock("Apple Inc.", 180.50),
    "bond"   => new Bond("US Treasury 10Y", 1000.00),
    "crypto" => new Cryptocurrency("Bitcoin", 27000.00)
];

// ==========================
// 4. Вспомогательные функции
// ==========================

// Вывод строки с переводом строки
function println(string $text = ""): void {
    echo $text . PHP_EOL;
}

// Запрос ввода от пользователя
function prompt(string $text): string {
    echo $text;
    return trim(fgets(STDIN));
}

// Показать список активов
function showAssets(array $assets): void {
    println("Текущие активы:");
    foreach ($assets as $key => $asset) {
        println(" - [$key] " . $asset->info() . " | Риск: " . $asset->getRiskLevel());
    }
    println();
}

// ==========================
// 5. Основное меню
// ==========================
while (true) {
    println("Меню:");
    println("1) Показать активы");
    println("2) Изменить цену актива на процент");
    println("0) Выход");
    $choice = prompt("Ваш выбор: ");

    switch ($choice) {
        case "1":
            showAssets($assets);
            break;

        case "2":
            showAssets($assets);
            $type = prompt("Введите ключ актива (stock/bond/crypto): ");
            if (!isset($assets[$type])) {
                println("Ошибка: такого актива нет.");
                break;
            }
            $percent = (float)prompt("Введите процент изменения (например, 5 или -3): ");
            $assets[$type]->simulateGrowth($percent);
            println("Новая цена: " . $assets[$type]->info());
            break;

        case "0":
            println("Выход из программы. До свидания!");
            exit;

        default:
            println("Неизвестная команда. Попробуйте снова.");
    }
}
