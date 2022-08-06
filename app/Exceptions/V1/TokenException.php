<?php

namespace App\Exceptions\V1;

use App\Exceptions\BaseException;

class TokenException extends BaseException
{
    public static function invalidToken()
    {
        return new self(
            'Invalid token',
            '401'
        );
    }
}
