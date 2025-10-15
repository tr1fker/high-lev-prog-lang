<?php

// Упрощенные классы без namespace для простоты
abstract class Artwork {
    protected string $title;
    protected string $author;
    protected int $year;
    protected float $baseValue;

    public function __construct(string $title, string $author, int $year, float $baseValue) {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->baseValue = $baseValue;
    }

    abstract public function display(): string;
    abstract public function appraiseValue(): float;

    public function getAuthor(): string {
        return $this->author;
    }

    public function getYear(): int {
        return $this->year;
    }

    public function info(): string {
        return "{$this->title} ({$this->author}, {$this->year}) - Оценка: " .
            number_format($this->appraiseValue(), 2, '.', ' ') . " руб.";
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getBaseValue(): float {
        return $this->baseValue;
    }
}

class Painting extends Artwork {
    private string $style;
    private string $medium;
    private float $conditionMultiplier;

    public function __construct(string $title, string $author, int $year, float $baseValue,
                                string $style, string $medium, float $conditionMultiplier = 1.0) {
        parent::__construct($title, $author, $year, $baseValue);
        $this->style = $style;
        $this->medium = $medium;
        $this->conditionMultiplier = max(0.1, min(2.0, $conditionMultiplier));
    }

    public function display(): string {
        return "Картина: {$this->title}\n" .
            "Стиль: {$this->style}, Техника: {$this->medium}\n" .
            "Состояние: " . ($this->conditionMultiplier > 1 ? 'Отличное' :
                ($this->conditionMultiplier < 1 ? 'Плохое' : 'Хорошее'));
    }

    public function appraiseValue(): float {
        $ageMultiplier = max(1.0, (date('Y') - $this->year) * 0.05);
        $styleMultiplier = $this->getStyleMultiplier();

        return $this->baseValue * $ageMultiplier * $styleMultiplier * $this->conditionMultiplier;
    }

    private function getStyleMultiplier(): float {
        return match($this->style) {
            'импрессионизм' => 2.5,
            'реализм' => 1.8,
            'абстракционизм' => 1.5,
            'сюрреализм' => 2.0,
            default => 1.2
        };
    }

    public function getStyle(): string {
        return $this->style;
    }

    public function getMedium(): string {
        return $this->medium;
    }
}

class Sculpture extends Artwork {
    private string $material;
    private float $weight;
    private string $condition;

    public function __construct(string $title, string $author, int $year, float $baseValue,
                                string $material, float $weight, string $condition = 'хорошее') {
        parent::__construct($title, $author, $year, $baseValue);
        $this->material = $material;
        $this->weight = $weight;
        $this->condition = $condition;
    }

    public function display(): string {
        return "Скульптура: {$this->title}\n" .
            "Материал: {$this->material}, Вес: {$this->weight} кг\n" .
            "Состояние: {$this->condition}";
    }

    public function appraiseValue(): float {
        $materialMultiplier = $this->getMaterialMultiplier();
        $weightMultiplier = min(3.0, $this->weight * 0.1);
        $conditionMultiplier = $this->getConditionMultiplier();
        $ageMultiplier = max(1.0, (date('Y') - $this->year) * 0.03);

        return $this->baseValue * $materialMultiplier * $weightMultiplier * $conditionMultiplier * $ageMultiplier;
    }

    private function getMaterialMultiplier(): float {
        return match($this->material) {
            'бронза' => 2.5,
            'мрамор' => 3.0,
            'дерево' => 1.5,
            'глина' => 1.2,
            default => 1.0
        };
    }

    private function getConditionMultiplier(): float {
        return match($this->condition) {
            'отличное' => 1.5,
            'хорошее' => 1.0,
            'удовлетворительное' => 0.7,
            'плохое' => 0.3,
            default => 0.5
        };
    }

    public function getMaterial(): string {
        return $this->material;
    }

    public function getWeight(): float {
        return $this->weight;
    }
}

class DigitalArt extends Artwork {
    private string $format;
    private int $resolution;
    private bool $isLimitedEdition;
    private int $editionNumber;

    public function __construct(string $title, string $author, int $year, float $baseValue,
                                string $format, int $resolution, bool $isLimitedEdition = false,
                                int $editionNumber = 1) {
        parent::__construct($title, $author, $year, $baseValue);
        $this->format = $format;
        $this->resolution = $resolution;
        $this->isLimitedEdition = $isLimitedEdition;
        $this->editionNumber = $editionNumber;
    }

    public function display(): string {
        $editionInfo = $this->isLimitedEdition ?
            "Ограниченная серия №{$this->editionNumber}" : "Без ограничений";

        return "Цифровое искусство: {$this->title}\n" .
            "Формат: {$this->format}, Разрешение: {$this->resolution}p\n" .
            "Тип: {$editionInfo}";
    }

    public function appraiseValue(): float {
        $resolutionMultiplier = min(2.0, $this->resolution / 1000);
        $editionMultiplier = $this->isLimitedEdition ? 2.0 : 0.5;
        $formatMultiplier = $this->getFormatMultiplier();
        $noveltyMultiplier = max(0.5, 2.0 - (date('Y') - $this->year) * 0.1);

        return $this->baseValue * $resolutionMultiplier * $editionMultiplier * $formatMultiplier * $noveltyMultiplier;
    }

    private function getFormatMultiplier(): float {
        return match($this->format) {
            '4K' => 1.8,
            '2K' => 1.5,
            'Full HD' => 1.2,
            'HD' => 1.0,
            default => 0.8
        };
    }

    public function getFormat(): string {
        return $this->format;
    }

    public function getResolution(): int {
        return $this->resolution;
    }

    public function isLimitedEdition(): bool {
        return $this->isLimitedEdition;
    }
}

// Создаем коллекцию художественных произведений
$artCollection = [
    new Painting("Звездная ночь", "Винсент Ван Гог", 1889, 50000000, "постимпрессионизм", "масло", 1.2),
    new Painting("Крик", "Эдвард Мунк", 1893, 45000000, "экспрессионизм", "масло, темпера", 0.9),
    new Sculpture("Давид", "Микеланджело", 1504, 80000000, "мрамор", 5600, "отличное"),
    new Sculpture("Мыслитель", "Огюст Роден", 1902, 35000000, "бронза", 180, "хорошее"),
    new DigitalArt("Каждый день: первые 5000 дней", "Бипл", 2021, 1000000, "4K", 4096, true, 1),
    new DigitalArt("Цифровая абстракция", "Алексей Петров", 2023, 50000, "2K", 2048, false)
];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Галерея искусств</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        .art-gallery {
            max-width: 1200px;
            margin: 0 auto;
        }
        .artwork {
            background: white;
            margin: 15px 0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .painting { border-left: 5px solid #e74c3c; }
        .sculpture { border-left: 5px solid #3498db; }
        .digital { border-left: 5px solid #2ecc71; }
        h2 { color: #2c3e50; margin-top: 0; }
        .details {
            background: #ecf0f1;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .value {
            font-weight: bold;
            color: #e74c3c;
            font-size: 1.2em;
        }
        .type {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            color: white;
            font-size: 0.8em;
        }
        .type-painting { background: #e74c3c; }
        .type-sculpture { background: #3498db; }
        .type-digital { background: #2ecc71; }
    </style>
</head>
<body>
<div class="art-gallery">
    <h1>🎨 Галерея искусств</h1>
    <p>Демонстрация наследования: Artwork → Painting, Sculpture, DigitalArt</p>

    <?php foreach ($artCollection as $art): ?>
        <div class="artwork <?= get_class($art) === 'Painting' ? 'painting' :
            (get_class($art) === 'Sculpture' ? 'sculpture' : 'digital') ?>">

            <?php
            $typeClass = match(get_class($art)) {
                'Painting' => 'type-painting',
                'Sculpture' => 'type-sculpture',
                'DigitalArt' => 'type-digital'
            };
            $typeName = match(get_class($art)) {
                'Painting' => 'Картина',
                'Sculpture' => 'Скульптура',
                'DigitalArt' => 'Цифровое искусство'
            };
            ?>

            <span class="type <?= $typeClass ?>"><?= $typeName ?></span>
            <h2><?= htmlspecialchars($art->getTitle()) ?></h2>

            <div class="details">
                <strong>Информация:</strong><br>
                <?= nl2br(htmlspecialchars($art->display())) ?>
            </div>

            <div>
                <strong>Автор:</strong> <?= htmlspecialchars($art->getAuthor()) ?><br>
                <strong>Год создания:</strong> <?= $art->getYear() ?><br>
                <strong>Базовая стоимость:</strong> <?= number_format($art->getBaseValue(), 2, '.', ' ') ?> руб.<br>
                <strong>Текущая оценка:</strong> <span class="value"><?= number_format($art->appraiseValue(), 2, '.', ' ') ?> руб.</span>
            </div>

            <div style="margin-top: 10px; font-style: italic;">
                <strong>Полная информация:</strong> <?= htmlspecialchars($art->info()) ?>
            </div>

            <?php if (method_exists($art, 'getStyle')): ?>
                <div><small>Стиль: <?= htmlspecialchars($art->getStyle()) ?></small></div>
            <?php endif; ?>

            <?php if (method_exists($art, 'getMaterial')): ?>
                <div><small>Материал: <?= htmlspecialchars($art->getMaterial()) ?></small></div>
            <?php endif; ?>

            <?php if (method_exists($art, 'getFormat')): ?>
                <div><small>Формат: <?= htmlspecialchars($art->getFormat()) ?></small></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div style="margin-top: 30px; padding: 20px; background: #34495e; color: white; border-radius: 10px;">
        <h3>📊 Статистика коллекции</h3>
        <?php
        $totalValue = array_reduce($artCollection, fn($sum, $art) => $sum + $art->appraiseValue(), 0);
        $averageValue = $totalValue / count($artCollection);
        $mostValuable = array_reduce($artCollection, fn($max, $art) =>
        $art->appraiseValue() > $max->appraiseValue() ? $art : $max, $artCollection[0]);
        ?>
        <p><strong>Общая стоимость коллекции:</strong> <?= number_format($totalValue, 2, '.', ' ') ?> руб.</p>
        <p><strong>Средняя стоимость произведения:</strong> <?= number_format($averageValue, 2, '.', ' ') ?> руб.</p>
        <p><strong>Самое ценное произведение:</strong> "<?= htmlspecialchars($mostValuable->getTitle()) ?>"
            (<?= number_format($mostValuable->appraiseValue(), 2, '.', ' ') ?> руб.)</p>
    </div>
</div>
</body>
</html>