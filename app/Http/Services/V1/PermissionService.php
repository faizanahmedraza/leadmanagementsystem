<?php

namespace App\Http\Services\V1;

use App\Exceptions\V1\PermissionException;
use App\Models\User;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;
use App\Models\Role;

class PermissionService
{
    public static function get()
    {
        return Permission::orderBy('id', 'desc')->get();
    }

    public static function assigPermissionsToRole(Role $role, Request $request)
    {
        if (!empty($role)) {
            try {
                return $role->syncPermissions($request->permissions);
            } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $exception) {
                throw PermissionException::invalidPermission();
            }
        }
    }

    public static function assignDirectPermissionToUser(Request $request, User $user)
    {
        if (!empty($request->permissions)) {
            try {
                return $user->syncPermissions($request->permissions);
            } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $exception) {
                throw PermissionException::invalidPermission();
            }
        }
    }

    public static function revokePermissions(Request $request, User $user)
    {
        try {
            return $user->revokePermissionTo($request->permissions);
        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $exception) {
            throw PermissionException::invalidPermission();
        }
    }

    public static function givePermissions(Request $request, User $user)
    {
        try {
            return $user->givePermissionTo($request->permissions);
        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $exception) {
            throw PermissionException::invalidPermission();
        }
    }
}
