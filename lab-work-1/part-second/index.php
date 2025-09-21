<?php
$result = "";
$input = "";
$steps = [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["text"])) {
    $input = $_POST["text"];

    $pipeline = [
        'trim',
        'mb_strtolower',
        'ucfirst'
    ];

    $steps[] = ["Название шага" => "Исходная строка", "Значение" => $input];

    $result = array_reduce(
        $pipeline,
        function ($carry, $func) use (&$steps) {
            $newVal = $func($carry);

            $steps[] = ["Название шага" => "После {$func}", "Значение" => $newVal];

            return $newVal;
        },
        $input
    );
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Конвейер функций</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9fb;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            margin-top: 60px;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 480px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="text"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            background: #4a90e2;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.25s ease;
        }
        button:hover {
            background: #357ab7;
        }
        .output {
            margin-top: 25px;
            text-align: left;
        }
        .step {
            padding: 10px;
            margin-bottom: 12px;
            border-left: 5px solid #4a90e2;
            background: #f4f6f8;
            border-radius: 6px;
            animation: fadeIn 0.4s ease;
        }
        .step strong {
            display: block;
            margin-bottom: 5px;
            color: #444;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🔠 Конвейер функций</h2>
    <form method="post">
        <input type="text" name="text" placeholder="Введите строку..." value="<?= htmlspecialchars($input) ?>">
        <br>
        <button type="submit">Преобразовать</button>
    </form>

    <?php if (!empty($steps)): ?>
        <div class="output">
            <?php foreach ($steps as $step): ?>
                <div class="step">
                    <strong><?= htmlspecialchars($step["Название шага"]) ?>:</strong>
                    <pre><?= htmlspecialchars($step["Значение"]) ?></pre>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
