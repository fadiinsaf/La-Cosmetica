<?php

namespace App\DTO;

class RegisterDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $role,
    ) {}
}