<?php

namespace App\Http\Services\V1;

use App\Exceptions\V1\RoleException;
use App\Helpers\TimeStampHelper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exceptions\V1\ModelException;
use App\Exceptions\V1\FailureException;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;


class RoleService
{
    // Admin and Customer Roles Are Restricted
    public static function restrictRoles()
    {
        return config('permission.restrict_roles');
    }

    public static function get($request)
    {
        $roles = Role::query()->with('permissions');

        if ($request->has('from_date')) {
            $from = TimeStampHelper::formateDate($request->from_date);
            $roles->whereDate('created_at', '>=', $from);
        }

        if ($request->has('to_date')) {
            $to = TimeStampHelper::formateDate($request->to_date);
            $roles->whereDate('created_at', '<=', $to);
        }

        if ($request->query('order_by')) {
            $roles->orderBy('id', $request->get('order_by'));
        } else {
            $roles->orderBy('id', 'desc');
        }

        return ($request->filled('pagination') && $request->get('pagination') == 'false')
            ? $roles->get()
            : $roles->paginate(\pageLimit($request));
    }

    public static function store($request): Role
    {
        $role = new Role();
        $role->name = Role::ROLES_PREFIXES['admin'] . $request->name;
        $role->save();

        if (!$role) {
            throw FailureException::serverError();
        }

        PermissionService::assigPermissionsToRole($role, $request);

        return $role;
    }

    public static function first($id)
    {
        $role = Role::with('permissions')->adminRoles()->find($id);

        if (!$role) {
            throw ModelException::dataNotFound();
        }

        return $role;
    }

    public static function update($request, Role $role): Role
    {
        if ($role->name == trim($request->name)) {
            $role->name = $request->name;
        } else {
            $role->name = Role::ROLES_PREFIXES['admin'] . $request->name;
        }

        $role->save();

        // re asssign new permissions
        PermissionService::assigPermissionsToRole($role, $request);

        if (!$role) {
            throw FailureException::serverError();
        }

        return $role;
    }

    public static function destroy(Role $role): void
    {
        $role->delete();
    }

    public static function assignRolesToUser($request, User $user)
    {
        if (!empty($request->get('roles'))) {
            try {
                return $user->assignRole($request['roles']);
            } catch (\Spatie\Permission\Exceptions\RoleDoesNotExist $exception) {
                throw RoleException::invalidRole();
            }
        }
    }

    public static function syncRolesToUser($request, User $user)
    {
        if (!empty($request->get('roles'))) {
            try {
                return $user->syncRoles($request['roles']);
            } catch (\Spatie\Permission\Exceptions\RoleDoesNotExist $exception) {
                throw RoleException::invalidRole();
            }
        }
    }
}
