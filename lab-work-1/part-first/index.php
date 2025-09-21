<?php
function getDayOfWeek($date) {
    $timestamp = strtotime($date);
    if (!$timestamp) {
        return "❌ Неверный формат даты!";
    }

    $days = [
        "Воскресенье",
        "Понедельник",
        "Вторник",
        "Среда",
        "Четверг",
        "Пятница",
        "Суббота"
    ];

    return "📅 $date → <b>" . $days[date("w", $timestamp)] . "</b>";
}

$result = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["date"])) {
    $result = getDayOfWeek($_POST["date"]);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Определение дня недели</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #74ebd5 0%, #9face6 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="date"] {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            margin-right: 10px;
        }
        button {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            background: #4a90e2;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #357abd;
        }
        .result {
            margin-top: 20px;
            font-size: 18px;
            color: #444;
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Определение дня недели</h2>
        <form method="post">
            <input type="date" name="date" required>
            <button type="submit">Узнать</button>
        </form>

        <?php if ($result): ?>
            <div class="result"><?= $result ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
