<?php

namespace App\Http\Businesses\V1;

use App\Http\Services\V1\PermissionService;

class PermissionBusiness
{
    public static function get()
    {
        return PermissionService::get();
    }
}
