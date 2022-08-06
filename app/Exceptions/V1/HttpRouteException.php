<?php

namespace App\Exceptions\V1;

use App\Exceptions\BaseException;

class HttpRouteException extends BaseException
{
    public static function routeNotFound(): self
    {
        return new self(
            'Not found!',
            '404'
        );
    }

    public static function methodNotAllowed(): self
    {
        return new self(
            'Route method not allowed!',
            '405'
        );
    }
}
