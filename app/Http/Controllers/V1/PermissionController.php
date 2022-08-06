<?php

namespace App\Http\Controllers\V1;

use App\Http\Businesses\V1\PermissionBusiness;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PermissionsResponse;

/**
 * @group Permissions Api
 * @authenticated
 */

class PermissionController extends Controller
{
    private $module;

    public function __construct()
    {
        $this->module = 'permissions';
        $ULP = '|' .'access_all'; //UPPER LEVEL PERMISSIONS
        $this->middleware('permission:' . $this->module . '_list'. $ULP, ['only' => ['get']]);
    }

    /**
     * Permission List
     * This api return the collection of all Permissions created.
     *
     * @header Authorization String required Example: Bearer TOKEN
     *
     * @responseFile 200 responses/V1/PermissionsResponse.json
     */

    public function get()
    {
        $permissions = PermissionBusiness::get();
        return (new PermissionsResponse($permissions));
    }
}
