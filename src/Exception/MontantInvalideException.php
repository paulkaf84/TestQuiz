<?php

namespace App\Exception;

class MontantInvalideException extends \Exception
{
    public function __construct($message = "Invalid amount", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}