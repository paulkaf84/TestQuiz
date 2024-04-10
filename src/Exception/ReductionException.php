<?php

namespace App\Exception;

class ReductionException extends \Exception
{
    public function __construct($message = "Invalid Rate", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}