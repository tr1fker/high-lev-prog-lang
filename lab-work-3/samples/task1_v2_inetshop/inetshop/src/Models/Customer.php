<?php

namespace App\Models;

class Customer
{
    private string $name;
    private string $email;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getInfo(): string
    {
        return "Клиент: {$this->name} ({$this->email})";
    }
}