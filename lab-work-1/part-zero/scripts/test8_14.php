<?php
$arr[0] = "PHP"; 
$arr[1] = "HTML"; 
$arr[2] = "CSS";

unset($arr[1]);

foreach($arr as $key => $value) {
    echo $value.'<br/>';
}
?>