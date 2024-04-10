<?php

namespace App\Exception;

class StockException extends \Exception
{
    public function __construct($message = "Stock insuffisant", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}