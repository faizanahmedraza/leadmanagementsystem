<?php

namespace App\Http\Businesses\V1;

use App\Exceptions\V1\RoleException;
use App\Http\Services\V1\RoleService;

class RoleBusiness
{
    public static function store($request)
    {
        return RoleService::store($request);
    }

    public static function get($request)
    {
        return RoleService::get($request);
    }

    public static function first(int $id)
    {
        return RoleService::first($id);
    }

    public static function update($request, int $id)
    {
        $role = RoleService::first($id);
        return RoleService::update($request, $role);
    }

    public static function destroy(int $id): void
    {
        $role = RoleService::first($id);
        if(count($role->users) > 0)
        {
            RoleException::customError('You cannot delete this role. It assigned to users.',403);
        }
        RoleService::destroy($role);
    }
}
