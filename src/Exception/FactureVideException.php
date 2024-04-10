<?php

namespace App\Exception;

class FactureVideException extends \Exception
{
    public function __construct($message = "Bill is empty! ", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}