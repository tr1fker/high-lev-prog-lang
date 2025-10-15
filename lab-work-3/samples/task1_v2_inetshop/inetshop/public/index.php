<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/Interfaces/Discountable.php';
require_once __DIR__ . '/../src/Traits/Loggable.php';
require_once __DIR__ . '/../src/Models/Product.php';
require_once __DIR__ . '/../src/Models/Book.php';
require_once __DIR__ . '/../src/Models/Electronic.php';
require_once __DIR__ . '/../src/Models/Cart.php';
require_once __DIR__ . '/../src/Models/Customer.php';

session_start();
use App\Models\Book;
use App\Models\Electronic;
use App\Models\Cart;
use App\Models\Customer;

// Seed
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [
        new Book("PHP для начинающих", 80, "Иван Иванов"),
        new Book("Паттерны проектирования", 60, "Гамма и др."),
        new Electronic("Смартфон Honor 200", 1200, "HonorBrand", 24),
        new Electronic("Наушники ProMax", 120, "SoundMax", 12),
    ];
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new Cart();
}
if (!isset($_SESSION['customer'])) {
    $_SESSION['customer'] = new Customer("Алексей Петров", "alex@example.com");
}

// Actions
$action = $_POST['action'] ?? null;
$pid = $_POST['product_id'] ?? null;

if ($action && $pid) {
    $product = null;
    foreach ($_SESSION['products'] as $p) {
        if ($p->getId() === $pid) { $product = $p; break; }
    }
    if ($product) {
        switch ($action) {
            case 'add_to_cart': $_SESSION['cart']->add($product); break;
            case 'remove_from_cart': $_SESSION['cart']->remove($product->getId()); break;
            case 'discount_10': $product->applyDiscount(10); break;
            case 'discount_20': $product->applyDiscount(20); break;
        }
    }
}
if (($action ?? null) === 'checkout') {
    $total = $_SESSION['cart']->total();
    $_SESSION['message'] = "Заказ оформлен. Итог: " . number_format($total, 2, '.', ' ') . " руб.";
    $_SESSION['cart']->clear();
}
if (($action ?? null) === 'clear_cart') {
    $_SESSION['cart']->clear();
    $_SESSION['message'] = "Корзина очищена.";
}

if (($action ?? null) === 'clear_session') {
    session_destroy();
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Интернет‑магазин (ООП)</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="wrap">
    <h1>Интернет‑магазин — итоговый мини‑проект (ООП: наследование, интерфейсы, трейты)</h1>
    <div class="muted">Демонстрация: Product → Book/Electronic, Discountable, Loggable, Cart, Customer</div>
    <div class="customer-info">
        <?= htmlspecialchars($_SESSION['customer']->getInfo()) ?>
    </div>
</header>

<main class="wrap grid">
    <section class="card">
        <h2>Каталог товаров</h2>
        <div class="list">
            <?php foreach ($_SESSION['products'] as $product): ?>
                <div class="item">
                    <div>
                        <div class="title"><?= htmlspecialchars($product->getInfo()) ?></div>
                        <div class="sub">ID: <span class="accent"><?= htmlspecialchars($product->getId()) ?></span></div>
                    </div>
                    <form method="post" class="btns">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product->getId()) ?>">
                        <button name="action" value="add_to_cart">В корзину</button>
                        <button name="action" value="discount_10">Скидка 10%</button>
                        <button name="action" value="discount_20">Скидка 20%</button>
                        <button type="button" class="details-btn" data-product-id="<?= htmlspecialchars($product->getId()) ?>">Подробнее</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <aside class="card">
        <h2>Корзина</h2>
        <div class="list">
            <?php foreach ($_SESSION['cart']->getItems() as $p): ?>
                <div class="item">
                    <div>
                        <div class="title"><?= htmlspecialchars($p->getName()) ?></div>
                        <div class="sub">Цена: <?= number_format($p->getPrice(), 2, '.', ' ') ?> руб.</div>
                    </div>
                    <form method="post" class="btns">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($p->getId()) ?>">
                        <button class="remove" name="action" value="remove_from_cart">Убрать</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="total">
            <div class="muted">Итого: <strong><?= number_format($_SESSION['cart']->total(), 2, '.', ' ') ?></strong> руб.</div>
            <form method="post">
                <button class="checkout" name="action" value="checkout">Оформить заказ</button>
            </form>
        </div>

        <div class="btns" style="margin:12px;">
            <form method="post">
                <button class="btn-clear-cart" name="action" value="clear_cart">Очистить корзину</button>
            </form>
            <form method="post">
                <button class="btn-clear-session" name="action" value="clear_session">Сбросить сессию</button>
            </form>
        </div>

        <?php if (!empty($_SESSION['message'])): ?>
            <div class="msg"><?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
        <?php endif; ?>
    </aside>
</main>

<!-- Модальное окно для деталей товара -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="modalBody"></div>
    </div>
</div>

<script>
    // JavaScript для модального окна
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('productModal');
        const modalBody = document.getElementById('modalBody');
        const closeBtn = document.querySelector('.close');

        // Обработчики для кнопок "Подробнее"
        document.querySelectorAll('.details-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');

                // Получаем информацию о товаре из сессии (в реальном приложении здесь был бы AJAX запрос)
                const products = <?= json_encode(array_map(function($p) {
                    return [
                        'id' => $p->getId(),
                        'name' => $p->getName(),
                        'price' => $p->getPrice(),
                        'info' => $p->getInfo(),
                        'type' => get_class($p)
                    ];
                }, $_SESSION['products'])) ?>;

                const product = products.find(p => p.id === productId);

                if (product) {
                    let details = `<h3>${product.name}</h3>`;
                    details += `<p><strong>Цена:</strong> ${product.price.toFixed(2)} руб.</p>`;
                    details += `<p><strong>Тип:</strong> ${product.type === 'App\\Models\\Book' ? 'Книга' : 'Электроника'}</p>`;
                    details += `<p><strong>Описание:</strong> ${product.info}</p>`;

                    if (product.type === 'App\\Models\\Book') {
                        details += `<p><strong>Категория:</strong> Учебная литература</p>`;
                    } else {
                        details += `<p><strong>Категория:</strong> Электронные устройства</p>`;
                    }

                    modalBody.innerHTML = details;
                    modal.style.display = 'block';
                }
            });
        });

        // Закрытие модального окна
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>