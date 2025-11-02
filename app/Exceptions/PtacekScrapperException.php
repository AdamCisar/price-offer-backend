<?php

namespace App\Exceptions;

use Exception;

class PtacekScrapperException extends Exception
{
    public static function invalidCredentials(): self
    {
        return new self('Nesprávne prihlasovacie údaje!');
    }
}
