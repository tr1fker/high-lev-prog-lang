<?php
$arr[0] = "PHP"; 
$arr[1] = "HTML"; 
$arr[2] = "CSS";

$arr[1] = "JAVASCRIPT";
$arr[]  = "JQUERY";

foreach($arr as $key => $value) { // при переборе: $key - индекс элемента массива, $value - значение элемента массива
    echo $value.'<br/>';
}
?>