<?php

namespace App\Exceptions\V1;

use App\Exceptions\BaseException;

class RoleException extends BaseException
{
    public static function roleCannotBeDelete(): self
    {
        return new self(
            'You do not have access to delete this role.',
            '422'
        );
    }

    public static function invalidRole(): self
    {
        return new self(
            'Role is invalid.',
            '422'
        );
    }

    public static function customError($message,$code): self
    {
        return new self(
            $message,
            $code
        );
    }
}
