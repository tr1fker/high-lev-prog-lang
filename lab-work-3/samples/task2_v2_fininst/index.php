<?php
// ----------------------
// Запускаем сессию, чтобы хранить данные об активах между обновлениями страницы
// ----------------------
session_start();

/**
 * Абстрактный класс Asset — базовый для всех финансовых инструментов
 */
abstract class Asset {
    protected string $name;   // Название актива
    protected float $price;   // Текущая цена

    // Конструктор: принимает название и начальную цену
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

    // Абстрактный метод: уровень риска (реализуется в дочерних классах)
    abstract public function getRiskLevel(): string;

    // Смоделировать рост/падение цены на заданный процент
    public function simulateGrowth(float $percent): void {
        $this->price += $this->price * ($percent / 100);
    }

    // Вернуть строку с информацией об активе
    public function info(): string {
        return "{$this->name}: " . number_format($this->price, 2, '.', ' ') . " USD";
    }
}

/**
 * Дочерние классы для разных типов активов
 */
class Stock extends Asset {
    public function getRiskLevel(): string {
        return "Средний риск (волатильность рынка акций)";
    }
}

class Bond extends Asset {
    public function getRiskLevel(): string {
        return "Низкий риск (фиксированный доход)";
    }
}

class Cryptocurrency extends Asset {
    public function getRiskLevel(): string {
        return "Высокий риск (сильная волатильность)";
    }
}

// ----------------------
// Инициализация активов в сессии (только при первом заходе)
// ----------------------
if (!isset($_SESSION['assets'])) {
    $_SESSION['assets'] = [
        "stock" => new Stock("Apple Inc.", 180.50),
        "bond" => new Bond("US Treasury 10Y", 1000.00),
        "crypto" => new Cryptocurrency("Bitcoin", 27000.00)
    ];
}

// Ссылка на массив активов в сессии
$assets = &$_SESSION['assets'];

// ----------------------
// Обработка формы
// ----------------------
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Если нажата кнопка сброса — уничтожаем сессию и перезагружаем страницу
    if (isset($_POST['reset'])) {
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Получаем данные из формы
    $type = $_POST['asset'] ?? null;             // выбранный актив
    $percent = (float)($_POST['percent'] ?? 0);  // процент изменения

    // Если актив существует — пересчитываем цену
    if ($type && isset($assets[$type])) {
        $assets[$type]->simulateGrowth($percent);
        $message = "Цена актива '{$assets[$type]->info()}' пересчитана (изменение {$percent}%).";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Финансовые инструменты</title>
    <!-- Подключаем внешний CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Кнопка переключения темы -->
    <button id="themeToggle">🌙 Тёмная тема</button>

    <h1>Финансовые инструменты</h1>

    <!-- Сообщение пользователю (например, после пересчёта цены) -->
    <?php if ($message): ?>
        <div class="msg"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Форма для изменения цены актива -->
    <form method="post">
        <label for="asset">Выберите актив:</label>
        <select name="asset" id="asset">
            <option value="stock">Акция (Apple Inc.)</option>
            <option value="bond">Облигация (US Treasury 10Y)</option>
            <option value="crypto">Криптовалюта (Bitcoin)</option>
        </select>

        <label for="percent">Введите изменение цены (%):</label>
        <input type="number" step="0.1" name="percent" id="percent" required>

        <button type="submit">Пересчитать</button>
    </form>

    <!-- Кнопка сброса сессии -->
    <form method="post">
        <button type="submit" name="reset" value="1" class="danger">Сбросить сессию</button>
    </form>

    <!-- Вывод текущих активов -->
    <h2>Текущие активы</h2>
    <?php foreach ($assets as $asset): ?>
        <div class="card">
            <div class="title"><?= $asset->info(); ?></div>
            <div class="risk"><?= $asset->getRiskLevel(); ?></div>
        </div>
    <?php endforeach; ?>

    <!-- JS: обработка темы -->
    <script>
        const toggleBtn = document.getElementById("themeToggle");

        // Функция переключения темы
        function toggleTheme() {
            document.documentElement.classList.toggle("dark-mode");
            if (document.documentElement.classList.contains("dark-mode")) {
                localStorage.setItem("theme", "dark");
                toggleBtn.textContent = "☀️ Светлая тема";
            } else {
                localStorage.setItem("theme", "light");
                toggleBtn.textContent = "🌙 Тёмная тема";
            }
        }

        // Навешиваем обработчик на кнопку
        toggleBtn.addEventListener("click", toggleTheme);

        // При загрузке страницы проверяем сохранённую тему
        document.addEventListener("DOMContentLoaded", () => {
            if (localStorage.getItem("theme") === "dark") {
                document.documentElement.classList.add("dark-mode");
                toggleBtn.textContent = "☀️ Светлая тема";
            }
        });
    </script>
</body>
</html>
