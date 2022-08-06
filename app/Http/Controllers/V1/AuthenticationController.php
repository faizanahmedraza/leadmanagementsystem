<?php

namespace App\Http\Controllers\V1;

use App\Http\Businesses\V1\AuthenticationBusiness;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ChangePasswordRequest;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Resources\SuccessResponse;
use App\Http\Resources\V1\AuthenticationResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Authentication
 */
class AuthenticationController extends Controller
{
    /**
     * Access Token Or Login
     * This function is useful for login, to return access token for users.
     *
     * @bodyParam email email required The username of user. Example: admin@my-app.com
     * @bodyParam password string required The password of user. Example: Abc*123*
     *
     * @header Client-ID string required
     * @header Client-Secret string required
     *
     * @responseFile 200 responses/V1/AuthenticationResponse.json
     * @responseFile 422 responses/ValidationResponse.json
     */

    public function login(LoginRequest $request)
    {
        $auth = (new AuthenticationBusiness())->verifyLoginInfo($request);
        return new AuthenticationResponse($auth);
    }

    /**
     * Logout
     * Hit api and session get out
     *
     * @authenticated
     *
     * @header Authorization string required
     *
     * @responseFile 200 responses/SuccessResponse.json
     * @responseFile 422 responses/ValidationResponse.json
     */
    public static function logout(Request $request)
    {
        (new AuthenticationBusiness())->logout($request);
        return new SuccessResponse([]);
    }

    /**
     * Change Password
     * change password request of user
     *
     * @authenticated
     *
     * @bodyParam password String required abcd1234 Example: abcd1234
     * @bodyParam password_confirmation String required  abcd1234 Example: abcd1234
     *
     * @responseFile 200 responses/SuccessResponse.json
     * @responseFile 422 responses/ValidationResponse.json
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        DB::beginTransaction();
        (new AuthenticationBusiness())->changePassword($request);
        DB::commit();
        return new SuccessResponse([]);
    }
}
