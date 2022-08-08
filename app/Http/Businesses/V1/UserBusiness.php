<?php

namespace App\Http\Businesses\V1;

use App\Exceptions\V1\RequestValidationException;
use App\Exceptions\V1\UnAuthorizedException;
use App\Exceptions\V1\UserException;
use App\Http\Services\V1\PermissionService;
use App\Http\Services\V1\RoleService;
use App\Http\Services\V1\UserService;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Http\Request;

class UserBusiness
{
    public static function first(int $id)
    {
        return UserService::first($id);
    }

    public static function store($request)
    {
        if ($request->has('avatar') && !empty($request->avatar) && !validate_base64($request->avatar, ['png', 'jpg', 'jpeg'])) {
            throw RequestValidationException::errorMessage('Invalid image. Base64 image string is required. Allowed formats are png,jpg,jpeg.');
        }
        // create user
        $user = UserService::store($request);

        //assigning roles to the user
        RoleService::assignRolesToUser($request, $user);

        // assign direct permission to user
        PermissionService::assignDirectPermissionToUser($request, $user);
    }

    public static function get($request)
    {
        // get users
        return UserService::get($request);
    }

    public static function update($request, int $id)
    {
        if ($request->has('avatar') && !empty($request->avatar) && !validate_base64($request->avatar, ['png', 'jpg', 'jpeg'])) {
            throw RequestValidationException::errorMessage('Invalid image. Base64 image string is required. Allowed formats are png,jpg,jpeg.');
        }

        $user = self::first($id);

        if ($user->id == Auth::id() && in_array($request->status, ['blocked', 'pending'])) {
            throw UserException::authUserRestrictStatus();
        }

        // update in users table
        UserService::update($request, $user);

        RoleService::syncRolesToUser($request, $user);

        PermissionService::assignDirectPermissionToUser($request, $user);
    }

    public static function destroy(int $id)
    {
        // delete user
        $user = self::first($id);

        if ($user->id == Auth::id()) {
            throw UserException::authUserDeleteRestrict();
        }

        UserService::destroy($user);
    }

    public static function toggleStatus($id, Request $request)
    {
        // get user
        $user = self::first($id);

        if ($user->id == Auth::id()) {
            throw UserException::authUserRestrictStatus();
        }
        // status toggle
        UserService::toggleStatus($user, $request);
    }

    public static function changePassword($id, Request $request)
    {
        $user = self::first($id);
        UserService::changeUserPassword($user, $request->password);
    }
}
