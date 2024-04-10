<?php

namespace App\Exception;

class MontantInfException extends \Exception
{
    public function __construct($message = "Amount is less than the bill", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}