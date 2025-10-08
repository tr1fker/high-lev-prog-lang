<?php
declare(strict_types=1);

/**
 * Функция для анализа массива и поиска дубликатов
 */
function analyzeArray(array $array): array {
    $result = [
        'original' => $array,
        'unique' => array_unique($array),
        'duplicates' => [],
        'duplicate_info' => [],
        'stats' => []
    ];
    
    // Находим дубликаты
    $valueCounts = array_count_values($array);
    foreach ($valueCounts as $value => $count) {
        if ($count > 1) {
            $result['duplicates'][] = $value;
            $result['duplicate_info'][$value] = $count;
        }
    }
    
    // Статистика
    $result['stats'] = [
        'total_elements' => count($array),
        'unique_elements' => count($result['unique']),
        'duplicate_count' => count($array) - count($result['unique']),
        'duplicate_types' => count($result['duplicates']),
        'most_common' => array_keys($valueCounts, max($valueCounts))[0] ?? null,
        'most_common_count' => max($valueCounts) ?: 0
    ];
    
    return $result;
}

// Исходные данные по умолчанию
$defaultArray = [
    'apple', 'banana', 'orange', 'apple', 'grape', 
    'banana', 'kiwi', 'mango', 'orange', 'pear',
    'apple', 'kiwi', 'pineapple', 'banana', 'grape'
];

// Обработка формы
$customArray = $defaultArray;
$analysis = analyzeArray($defaultArray);
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    
    if (isset($_POST['use_default'])) {
        $customArray = $defaultArray;
        $analysis = analyzeArray($defaultArray);
    }
    elseif (isset($_POST['custom_array'])) {
        $input = trim($_POST['custom_array']);
        if (!empty($input)) {
            // Разбиваем ввод на массив по запятым или переносам строк
            $customArray = preg_split('/[\s,]+/', $input);
            $customArray = array_map('trim', $customArray);
            $customArray = array_filter($customArray, function($value) {
                return $value !== '';
            });
            $customArray = array_values($customArray);
            
            if (!empty($customArray)) {
                $analysis = analyzeArray($customArray);
            } else {
                $customArray = $defaultArray;
                $analysis = analyzeArray($defaultArray);
            }
        }
    }
    elseif (isset($_POST['add_sample'])) {
        $sampleData = [
            'JavaScript', 'Python', 'Java', 'C++', 'PHP',
            'Python', 'JavaScript', 'Ruby', 'Go', 'PHP',
            'Rust', 'Java', 'TypeScript', 'Python', 'C#'
        ];
        $customArray = $sampleData;
        $analysis = analyzeArray($sampleData);
    }
}

$stats = $analysis['stats'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 3 - Анализ массива с дубликатами</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Лабораторная работа №2</h1>
            <p>Задание 3 - Отображение массива с подсветкой дубликатов</p>
        </div>

        <div class="task-description">
            <h2>📋 Описание задания</h2>
            <p>Отобразить массив в сетке, дубликаты подчеркнуть стилем. Показать визуальное представление исходного массива с выделением повторяющихся элементов.</p>
        </div>

        <div class="controls-section">
            <h3>🎛️ Управление массивом</h3>
            
            <div class="controls-grid">
                <form method="POST">
                    <div class="form-group">
                        <label for="custom_array">Введите свой массив (через запятую или пробел):</label>
                        <textarea id="custom_array" name="custom_array" 
                                  placeholder="например: apple, banana, orange, apple, grape, banana"
                                  rows="4"><?= htmlspecialchars(implode(', ', $customArray)) ?></textarea>
                    </div>
                    <button type="submit" name="custom_submit" class="btn">
                        🔍 Проанализировать массив
                    </button>
                </form>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <form method="POST">
                        <button type="submit" name="use_default" class="btn btn-success">
                            🍎 Использовать фруктовый массив
                        </button>
                    </form>
                    
                    <form method="POST">
                        <button type="submit" name="add_sample" class="btn">
                            💻 Использовать языки программирования
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Статистика -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_elements'] ?></div>
                <div class="stat-label">Всего элементов</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['unique_elements'] ?></div>
                <div class="stat-label">Уникальных элементов</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['duplicate_count'] ?></div>
                <div class="stat-label">Дубликатов</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['duplicate_types'] ?></div>
                <div class="stat-label">Типов дубликатов</div>
            </div>
        </div>

        <!-- Визуализация массивов -->
        <div class="array-display-section">
            <h3 class="section-title">📊 Визуализация массивов</h3>
            
            <div class="array-grid">
                <!-- Исходный массив -->
                <div class="array-box">
                    <h4>🎯 Исходный массив</h4>
                    <div class="array-items">
                        <?php if (!empty($analysis['original'])): ?>
                            <?php foreach ($analysis['original'] as $index => $item): ?>
                                <div class="array-item <?= in_array($item, $analysis['duplicates']) ? 'duplicate' : '' ?>">
                                    <?= htmlspecialchars($item) ?>
                                    <div class="array-index">[<?= $index ?>]</div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">Массив пуст</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Уникальные элементы -->
                <div class="array-box">
                    <h4>✅ Уникальные элементы</h4>
                    <div class="array-items">
                        <?php if (!empty($analysis['unique'])): ?>
                            <?php foreach ($analysis['unique'] as $index => $item): ?>
                                <div class="array-item unique">
                                    <?= htmlspecialchars($item) ?>
                                    <div class="array-index">[<?= $index ?>]</div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">Нет уникальных элементов</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Легенда -->
            <div class="color-legend">
                <div class="legend-item">
                    <div class="legend-color legend-normal"></div>
                    <span>Уникальные элементы (встречаются 1 раз)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-duplicate"></div>
                    <span>Дубликаты (встречаются 2+ раза)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-unique"></div>
                    <span>Только уникальные элементы</span>
                </div>
            </div>
        </div>

        <!-- Анализ дубликатов -->
        <div class="duplicate-analysis">
            <h4>🔍 Детальный анализ дубликатов</h4>
            
            <?php if (!empty($analysis['duplicates'])): ?>
                <div class="duplicate-list">
                    <?php foreach ($analysis['duplicate_info'] as $value => $count): ?>
                        <div class="duplicate-item">
                            <?= htmlspecialchars($value) ?>
                            <span class="duplicate-count">×<?= $count ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($stats['most_common']): ?>
                    <div style="text-align: center; margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 8px;">
                        <strong>🏆 Самый частый элемент:</strong> 
                        "<?= htmlspecialchars($stats['most_common']) ?>" 
                        (встречается <?= $stats['most_common_count'] ?> раз)
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-duplicates">
                    ✅ В массиве нет дубликатов - все элементы уникальны!
                </div>
            <?php endif; ?>
        </div>

        <!-- Сравнение -->
        <div class="array-comparison">
            <div class="comparison-box">
                <h4>📈 Эффективность массива</h4>
                <div style="text-align: center; margin: 20px 0;">
                    <div style="font-size: 3em; color: #667eea; margin-bottom: 10px;">
                        <?= round(($stats['unique_elements'] / $stats['total_elements']) * 100, 1) ?>%
                    </div>
                    <div style="color: #666;">Уникальность данных</div>
                </div>
                <div style="background: #e9ecef; height: 20px; border-radius: 10px; overflow: hidden; margin: 20px 0;">
                    <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); 
                                height: 100%; width: <?= ($stats['unique_elements'] / $stats['total_elements']) * 100 ?>%;">
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9em; color: #666;">
                    <span>Уникальные: <?= $stats['unique_elements'] ?></span>
                    <span>Дубликаты: <?= $stats['duplicate_count'] ?></span>
                </div>
            </div>

            <div class="comparison-box">
                <h4>📋 Сводка данных</h4>
                <div style="line-height: 2;">
                    <div><strong>Всего элементов:</strong> <?= $stats['total_elements'] ?></div>
                    <div><strong>Уникальных:</strong> <?= $stats['unique_elements'] ?></div>
                    <div><strong>Дубликатов:</strong> <?= $stats['duplicate_count'] ?></div>
                    <div><strong>Типов дубликатов:</strong> <?= $stats['duplicate_types'] ?></div>
                    <?php if ($stats['most_common']): ?>
                        <div><strong>Самый частый:</strong> "<?= htmlspecialchars($stats['most_common']) ?>"</div>
                        <div><strong>Количество:</strong> <?= $stats['most_common_count'] ?> раз</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px; color: #666; font-size: 0.9em;">
            <p>Лабораторная работа по высокоуровневым языкам программирования</p>
            <p>Вариант 8 - Задание 3 | Подсветка дубликатов | CSS Grid</p>
        </div>
    </div>
</body>
</html>