<?php

namespace App\Exceptions\V1;

use App\Exceptions\BaseException;

class ModelException extends BaseException
{
    public static function dataNotFound(): self
    {
        return new self(
            'Data trying to access does not exist.',
            '404'
        );
    }

    public static function invalidKey($key): self
    {
        return new self(
            'Please enter valid '. $key,
            '422'
        );
    }
}
