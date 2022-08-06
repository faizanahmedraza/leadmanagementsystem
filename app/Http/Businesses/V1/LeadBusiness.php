<?php

namespace App\Http\Businesses\V1;

use App\Exceptions\V1\RequestValidationException;
use App\Http\Services\V1\LeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadBusiness
{
    public static function first(int $id)
    {
        $lead = LeadService::first($id);
        return $lead;
    }

    public static function store($request)
    {
        // create lead
        LeadService::store($request);
    }

    public static function get($request)
    {
        // get lead
        return LeadService::get($request);
    }

    public static function update($request, int $id)
    {
        $lead = LeadService::first($id);

        // update in leads table
        LeadService::update($request, $lead);
    }

    public static function destroy(int $id): void
    {
        // delete lead
        $lead = LeadService::first($id);
        LeadService::destroy($lead);
    }

    public static function toggleStatus($id, Request $request)
    {
        // get lead
        $lead = LeadService::first($id);
        // status toggle
        LeadService::toggleStatus($lead, $request);
    }

    public static function assignLeads(Request $request)
    {
        $user = UserBusiness::first((int)$request->user_id);
        if (Auth::id() == $user->id) {
            throw RequestValidationException::errorMessage("You can't assign lead yourself!");
        }
        LeadService::assignLeads($user, $request);
    }

    public static function comments(Request $request)
    {
        $lead = self::first($request->lead_id);
        LeadService::comments($request, $lead);
    }

    public static function getComments($id)
    {
        return LeadService::getComments($id);
    }
}
