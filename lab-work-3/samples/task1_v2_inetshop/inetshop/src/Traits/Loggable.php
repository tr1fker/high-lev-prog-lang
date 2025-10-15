<?php
namespace App\Traits;

trait Loggable {
    protected function log(string $message): void {
        echo "<div class='log'>[LOG] {$message}</div>";
    }
}
