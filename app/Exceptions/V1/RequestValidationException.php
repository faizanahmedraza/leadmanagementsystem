<?php

namespace App\Exceptions\V1;

use App\Exceptions\BaseException;

class RequestValidationException extends BaseException
{
    public static function errorMessage($message, $code = 422): self
    {
        return new self(
            $message,
            $code
        );
    }

    public static function invalidValue($field): self
    {
        return new self(
            'Invalid value supplied for '. $field .' field ',
            '422'
        );
    }
}
