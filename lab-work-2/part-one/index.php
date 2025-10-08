<?php
declare(strict_types=1);

/**
 * Функция для нахождения ключей массива, значения которых больше заданного порога
 * @param array $array Исходный массив
 * @param int|float $threshold Пороговое значение
 * @return array Массив ключей, значения которых больше порога
 */
function findKeysAboveThreshold(array $array, $threshold): array {
    $result = [];
    foreach ($array as $key => $value) {
        if ($value > $threshold) {
            $result[] = $key;
        }
    }
    return $result;
}

// Исходный массив для работы
$originalArray = [
    0 => 10,
    1 => 25,
    2 => 8,
    3 => 45,
    4 => 15,
    5 => 32,
    6 => 18,
    7 => 50,
    8 => 5,
    9 => 28,
    10 => 12,
    11 => 38,
    12 => 22,
    13 => 41,
    14 => 7
];

// Обработка формы
$threshold = 20;
$keysAboveThreshold = [];
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['threshold'])) {
    $submitted = true;
    $threshold = (int)$_POST['threshold'];
    $keysAboveThreshold = findKeysAboveThreshold($originalArray, $threshold);
}

// Статистика массива
$minValue = min($originalArray);
$maxValue = max($originalArray);
$averageValue = array_sum($originalArray) / count($originalArray);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 1 - Ключи выше порога</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Лабораторная работа №2</h1>
            <p>Задание 1 - Поиск ключей массива выше порога</p>
        </div>

        <div class="task-description">
            <h2>📋 Описание задания</h2>
            <p>Найти ключи массива, значения которых больше заданного порога. Реализовано без использования встроенных функций поиска ключей.</p>
        </div>

        <div class="array-display">
            <h3>🔢 Исходный массив для анализа</h3>
            <div class="array-items">
                <?php foreach ($originalArray as $key => $value): ?>
                    <div class="array-item">
                        <?= $value ?>
                        <div class="array-index">[<?= $key ?>]</div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="array-stats">
                <div class="stat-item">
                    <div class="stat-value"><?= $minValue ?></div>
                    <div class="stat-label">Минимальное значение</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?= number_format($maxValue, 1) ?></div>
                    <div class="stat-label">Максимальное значение</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?= number_format($averageValue, 1) ?></div>
                    <div class="stat-label">Среднее значение</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?= count($originalArray) ?></div>
                    <div class="stat-label">Всего элементов</div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 style="text-align: center; margin-bottom: 20px; color: #333;">🎯 Установите пороговое значение</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="threshold">Пороговое значение:</label>
                    <input type="number" id="threshold" name="threshold" 
                           value="<?= htmlspecialchars((string)$threshold) ?>" 
                           step="1" required
                           placeholder="Введите любое число">
                    <small style="display: block; margin-top: 5px; color: #666;">
                        Диапазон значений в массиве: от <?= $minValue ?> до <?= $maxValue ?>
                    </small>
                </div>
                
                <button type="submit" class="btn">
                    🔍 Найти ключи выше порога
                </button>
            </form>
        </div>

        <?php if ($submitted): ?>
        <div class="results-section">
            <h2>📊 Результаты поиска</h2>
            
            <div class="threshold-display">
                Пороговое значение: <strong><?= $threshold ?></strong>
            </div>

            <?php if (!empty($keysAboveThreshold)): ?>
                <div class="keys-result">
                    <h3>✅ Найдено ключей: <?= count($keysAboveThreshold) ?></h3>
                    <p>Ключи со значениями больше <?= $threshold ?>:</p>
                    <div class="keys-list">
                        <?php foreach ($keysAboveThreshold as $key): ?>
                            <div class="key-item">
                                Ключ [<?= $key ?>] = <?= $originalArray[$key] ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-keys">
                    <h3>❌ Ключи не найдены</h3>
                    <p>В массиве нет элементов со значениями больше <?= $threshold ?></p>
                </div>
            <?php endif; ?>

            <div class="array-values">
                <h4 style="text-align: center; margin-bottom: 15px;">📈 Все значения массива:</h4>
                <?php foreach ($originalArray as $key => $value): ?>
                    <div class="value-item">
                        <span class="value-key">Ключ [<?= $key ?>]:</span>
                        <span class="value-number <?= $value > $threshold ? 'value-above' : 'value-below' ?>">
                            <?= $value ?> 
                            <?= $value > $threshold ? '✅' : '❌' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px; color: #666; font-size: 0.9em;">
            <p>Лабораторная работа по высокоуровневым языкам программирования</p>
            <p>Вариант 8 - Задание 1</p>
        </div>
    </div>
</body>
</html>