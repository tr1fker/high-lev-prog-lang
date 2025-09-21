<?php
$input = "";
$result = [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["numbers"])) {
    $input = $_POST["numbers"];

    $array = array_map('trim', explode(',', $input));

    $result = array_filter($array, fn($val) => $val !== '');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Преобразование строки в массив</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ffe29f, #ffa99f);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #fff;
            margin-top: 60px;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            width: 500px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background: #ff7b54;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #e06b40; }
        .output {
            margin-top: 25px;
            text-align: left;
        }
        .step {
            padding: 12px;
            margin-bottom: 12px;
            background: #f4f6f8;
            border-left: 5px solid #ff7b54;
            border-radius: 6px;
            animation: fadeIn 0.4s ease;
            font-family: monospace;
        }
        .step strong { display: block; margin-bottom: 6px; color: #444; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🔢 Строка → Массив</h2>
    <form method="post">
        <input type="text" name="numbers" placeholder="Введите числа через запятую..." value="<?= htmlspecialchars($input) ?>">
        <br>
        <button type="submit">Преобразовать</button>
    </form>

    <?php if (!empty($result)): ?>
        <div class="output">
            <div class="step">
                <strong>Исходная строка:</strong>
                <?= htmlspecialchars($input) ?>
            </div>
            <div class="step">
                <strong>Преобразованный массив:</strong>
                <pre><?= htmlspecialchars(print_r($result, true)) ?></pre>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
