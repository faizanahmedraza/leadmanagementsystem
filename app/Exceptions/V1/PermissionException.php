<?php

namespace App\Exceptions\V1;

use App\Exceptions\BaseException;

class PermissionException extends BaseException
{
    public static function invalidPermission(): self
    {
        return new self(
            'Permission is undefined.',
            '422'
        );
    }
}
