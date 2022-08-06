<?php

namespace App\Http\Controllers\V1;

use App\Http\Businesses\V1\LeadBusiness;
use App\Http\Controllers\Controller;

use App\Http\Requests\V1\AssignLeadRequest;
use App\Http\Requests\V1\CommentRequest;
use App\Http\Requests\V1\ListRequest;
use App\Http\Requests\V1\LeadRequest;
use App\Http\Resources\SuccessResponse;

use App\Http\Resources\V1\LeadCommentResponse;
use App\Http\Resources\V1\LeadsResponse;
use App\Http\Resources\V1\LeadResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Leads Management
 * @authenticated
 */
class LeadController extends Controller
{
    private $module;

    public function __construct()
    {
        $this->module = 'leads';
        $ULP = '|' . $this->module . '_all|access_all'; //UPPER LEVEL PERMISSIONS
        $this->middleware('permission:' . $this->module . '_read' . $ULP, ['only' => ['first', 'get', 'search']]);
        $this->middleware('permission:' . $this->module . '_create' . $ULP, ['only' => ['store']]);
        $this->middleware('permission:' . $this->module . '_update' . $ULP, ['only' => ['update']]);
        $this->middleware('permission:' . $this->module . '_delete' . $ULP, ['only' => ['destroy']]);
        $this->middleware('permission:' . $this->module . '_toggle_status' . $ULP, ['only' => ['toggleStatus']]);
        $this->middleware('permission:' . $this->module . '_assign' . $ULP, ['only' => ['assignLeads']]);
        $this->middleware('permission:' . $this->module . '_comments' . $ULP, ['only' => ['getLeadComments','comments']]);
    }

    /**
     * Create Leads
     * This api create new lead.
     *
     * @bodyParam  name String required
     * @bodyParam  description String
     * @bodyParam  phone email
     * @bodyParam  email String
     * @bodyParam  address String
     * @bodyParam  status string ex: pending,active,completed
     *
     * @responseFile 200 responses/V1/LeadResponse.json
     * @responseFile 422 responses/ValidationResponse.json
     */

    public function store(LeadRequest $request)
    {
        DB::beginTransaction();
        LeadBusiness::store($request);
        DB::commit();
        return new SuccessResponse([]);
    }

    /**
     * Get Leads
     * This will return leads list.
     *
     * @urlParam leads string 1,2,3,4
     * @urlParam name string ex: abc
     * @urlParam status string ex: pending,active,completed
     * @urlParam order_by string ex: asc/desc
     * @urlParam from_date string Example: Y-m-d
     * @urlParam to_date string Example: Y-m-d
     * @urlParam pagination boolean
     * @urlParam page_limit integer
     * @urlParam page integer
     *
     * @responseFile 200 responses/V1/LeadsResponse.json
     * @responseFile 422 responses/ValidationResponse.json
     */

    public function get(ListRequest $request)
    {
        $leads = LeadBusiness::get($request);
        return (new LeadsResponse($leads));
    }

    /**
     * Show Lead Details
     * This api show the lead details.
     *
     * @urlParam  lead_id required Integer
     *
     * @responseFile 200 responses/V1/LeadResponse.json
     * @responseFile 422 responses/ValidationResponse.json
     */
    public function first(int $id)
    {
        $lead = LeadBusiness::first($id);
        return (new LeadResponse($lead));
    }


    /**
     * Update Lead Details.
     * This api update lead details
     *
     * @bodyParam  name String required
     * @bodyParam  description String
     * @bodyParam  phone email
     * @bodyParam  email String
     * @bodyParam  address String
     * @bodyParam  status String required ex: pending,active,completed
     *
     * @urlParam  lead_id Integer required
     *
     * @responseFile 422 responses/ValidationResponse.json
     */

    public function update(LeadRequest $request, int $id)
    {
        DB::beginTransaction();
        LeadBusiness::update($request, $id);
        DB::commit();
        return new SuccessResponse([]);
    }

    /**
     * Delete Lead
     *
     * This api delete lead
     *
     * @urlParam  lead_id required Integer
     *
     * @responseFile 200 responses/SuccessResponse.json
     * @responseFile 401 responses/UnAuthorizedResponse.json
     */

    public function destroy(int $id)
    {
        LeadBusiness::destroy($id);
        return new SuccessResponse([]);
    }

    /**
     * Toggle Lead Status
     * This api update the leads status to active or completed
     *
     * @urlParam lead_id integer required
     *
     * @responseFile 200 responses/SuccessResponse.json
     * @responseFile 401 responses/UnAuthorizedResponse.json
     */

    public function toggleStatus(int $id, Request $request)
    {
        LeadBusiness::toggleStatus($id, $request);
        return new SuccessResponse([]);
    }

    /**
     * Assign Leads to User
     * This api update the leads status to active or deactive
     * other then customers.
     *
     * @bodyParam leads array integer required
     *
     * @responseFile 200 responses/SuccessResponse.json
     */

    public function assignLeads(AssignLeadRequest $request)
    {
        LeadBusiness::assignLeads($request);
        return new SuccessResponse([]);
    }

    /**
     * Store Comments
     * This api for comments on leads
     *
     * @bodyParam lead_id Integer required
     * @bodyParam description String required
     *
     * @responseFile 200 responses/SuccessResponse.json
     */
    public function comments(CommentRequest $request)
    {
        LeadBusiness::comments($request);
        return new SuccessResponse([]);
    }

    /**
     * Get Lead Comments
     * This api for comments on leads
     *
     * @urlParam lead_id integer required
     *
     * @responseFile 200 responses/SuccessResponse.json
     */
    public function getLeadComments(int $id)
    {
        $comments = LeadBusiness::getComments($id);
        return (new LeadCommentResponse($comments));
    }
}
