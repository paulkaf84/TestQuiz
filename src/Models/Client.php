<?php

namespace App\Models;

class Client
{
    public function __construct(
        public string $name,
        public string $type
    )
    {
    }
}