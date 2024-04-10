<?php

namespace App\Exception;

class MontantSupException extends \Exception
{
    public function __construct($message = "Amount is gather than the bill", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}