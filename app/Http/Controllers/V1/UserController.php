<?php

namespace App\Http\Controllers\V1;

use App\Http\Businesses\V1\UserBusiness;
use App\Http\Controllers\Controller;

use App\Http\Requests\V1\ListRequest;
use App\Http\Requests\V1\UserRequest;
use App\Http\Requests\V1\UserStatusRequest;
use App\Http\Resources\SuccessResponse;

use App\Http\Resources\V1\UserResponse;
use App\Http\Resources\V1\UsersResponse;
use Illuminate\Support\Facades\DB;

/**
 * @group Users Management
 * @authenticated
 */
class UserController extends Controller
{
    private $module;

    public function __construct()
    {
        $this->module = 'users';
        $ULP = '|' . $this->module . '_all|access_all'; //UPPER LEVEL PERMISSIONS
        $this->middleware('permission:' . $this->module . '_read' . $ULP, ['only' => ['first', 'get', 'search']]);
        $this->middleware('permission:' . $this->module . '_create' . $ULP, ['only' => ['store']]);
        $this->middleware('permission:' . $this->module . '_update' . $ULP, ['only' => ['update']]);
        $this->middleware('permission:' . $this->module . '_delete' . $ULP, ['only' => ['destroy']]);
        $this->middleware('permission:' . $this->module . '_toggle_status' . $ULP, ['only' => ['toggleStatus']]);
    }

    /**
     * Create Users
     * This api create new user.
     *
     * @bodyParam  first_name String required
     * @bodyParam  last_name String required
     * @bodyParam  email email required
     * @bodyParam  password String
     * @bodyParam  password_confirmation String
     * @bodyParam  status string ex: pending,active,blocked
     * @bodyParam  roles Array required ex: [1,2,3]
     * @bodyParam  permissions Array ex: [1,2,3]
     * @bodyParam  avatar String ex: base64image
     *
     * @responseFile 200 responses/V1/UserResponse.json
     * @responseFile 422 responses/ValidationResponse.json
     * @responseFile 401 responses/UnAuthorizedResponse.json
     */

    public function store(UserRequest $request)
    {
        DB::beginTransaction();
        UserBusiness::store($request);
        DB::commit();
        return new SuccessResponse([]);
    }

    /**
     * Get Users
     * This will return logged in user profile.
     *
     * @urlParam users string 1,2,3,4
     * @urlParam email string ex: abc.com,xyz.co
     * @urlParam first_name string
     * @urlParam last_name string
     * @urlParam full_name string
     * @urlParam status string ex: pending,active,blocked
     * @urlParam order_by string ex: asc/desc
     * @urlParam from_date string Example: Y-m-d
     * @urlParam to_date string Example: Y-m-d
     * @urlParam pagination boolean
     * @urlParam page_limit integer
     * @urlParam page integer
     *
     * @responseFile 200 responses/V1/UsersResponse.json
     * @responseFile 422 responses/ValidationResponse.json
     * @responseFile 401 responses/UnAuthorizedResponse.json
     */

    public function get(ListRequest $request)
    {
        $users = UserBusiness::get($request);
        return (new UsersResponse($users));
    }

    /**
     * Show User Details
     * This api show the uer details.
     *
     * @urlParam  user_id required Integer
     *
     * @responseFile 200 responses/V1/UserResponse.json
     * @responseFile 422 responses/ValidationResponse.json
     * @responseFile 401 responses/UnAuthorizedResponse.json
     */
    public function first(int $id)
    {
        $user = UserBusiness::first($id);
        return (new UserResponse($user));
    }


    /**
     * Update User Details.
     * This api update user details
     *
     * @bodyParam  first_name String required
     * @bodyParam  last_name String required
     * @bodyParam  status string required ex: pending,active,blocked
     * @bodyParam  roles Array required ex: [1,2,3]
     * @bodyParam  permissions Array ex: [1,2,3]
     * @bodyParam  avatar String ex: base64image
     *
     * @urlParam  user_id Integer required
     *
     * @responseFile 200 responses/V1/UserResponse.json
     * @responseFile 422 responses/ValidationResponse.json
     * @responseFile 401 responses/UnAuthorizedResponse.json
     */

    public function update(UserRequest $request, int $id)
    {
        DB::beginTransaction();
        UserBusiness::update($request, $id);
        DB::commit();
        return new SuccessResponse([]);
    }

    /**
     * Delete User
     *
     * This api delete user
     *
     * @urlParam  user_id required Integer
     *
     * @responseFile 200 responses/SuccessResponse.json
     * @responseFile 401 responses/UnAuthorizedResponse.json
     */

    public function destroy(int $id)
    {
        UserBusiness::destroy($id);
        return new SuccessResponse([]);
    }

    /**
     * Toggle User Status
     * This api update the users status to active or deactive
     * other then customers.
     *
     * @urlParam user_id integer required
     *
     * @responseFile 200 responses/SuccessResponse.json
     * @responseFile 401 responses/UnAuthorizedResponse.json
     */

    public static function toggleStatus(int $id, UserStatusRequest $request)
    {
        UserBusiness::toggleStatus($id, $request);
        return new SuccessResponse([]);
    }
}
