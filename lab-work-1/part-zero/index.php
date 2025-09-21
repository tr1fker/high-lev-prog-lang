<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>PHP Scripts Loader</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h2>Доступные скрипты</h2>

<?php
$scripts = glob("scripts/*.php");

foreach ($scripts as $i => $script) {
    $name = basename($script);
    $id = "output_" . $i;
    echo "<button class='script-btn' onclick=\"loadScript('$script', '$id', this)\">$name</button>";
    echo "<div id='$id' class='output'></div>";
}
?>

<script src="assets/js/main.js"></script>
</body>
</html>
