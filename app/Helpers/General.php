<?php

use App\Helpers\TimeStampHelper;

if (!function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

if (!function_exists('clean')) {
    function clean($value)
    {
        $value = trim(strtolower($value));

        if (strpos($value, ',') !== false) {
            return explode(",", $value);
        }

        return $value;
    }
}

if (!function_exists('getStatus')) {
    function getStatus($statusArray, $request)
    {
        $flipArr = array_flip(explode(',', $request));
        return array_intersect_key($statusArray, $flipArr);
    }
}

if (!function_exists('validate_base64')) {
    function validate_base64($value, array $allowedMime)
    {
        //check base64 string
        if (strpos($value, ';base64') !== false) {
            list($format, $data) = explode(';', $value);
            if (\Illuminate\Support\Str::contains($format, ['/'])) {
                list(, $format) = explode('/', $format);
            }
            if (\Illuminate\Support\Str::contains($data, [','])) {
                list(, $data) = explode(',', $data);
            }
        } else {
            return false;
        }

        // check file format
        if (!in_array($format, $allowedMime)) {
            return false;
        }

        // check base64 format
        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
            return false;
        }

        return true;
    }
}

if (!function_exists('GetDomainFromUrl')) {
    function GetDomainFromUrl($domain)
    {
        $parseDomain = parse_url(trim(strtolower($domain)));
        if (isset($parseDomain['host'])) {
            return $parseDomain['host'];
        }
        return $parseDomain['path'];
    }
}

if (!function_exists('recurringInvoiceDate')) {
    function recurringInvoiceDate($recurringType, $incStartDate = null)
    {
        if (is_null($incStartDate)) {
            $incStartDate = date('Y-m-d');
        }
        switch ($recurringType) {
            case CustomerServiceRequest::RECURRING_TYPE[0]:
                //weekly
                return TimeStampHelper::incrementInDate($incStartDate, ' +1 week') . " 00:00:00";
                break;
            case CustomerServiceRequest::RECURRING_TYPE[1]:
                //monthly
                return TimeStampHelper::incrementInDate($incStartDate, ' +1 month') . " 00:00:00";
                break;
            case CustomerServiceRequest::RECURRING_TYPE[2]:
                //quarterly
                return TimeStampHelper::incrementInDate($incStartDate, ' +3 months') . " 00:00:00";
                break;
            case CustomerServiceRequest::RECURRING_TYPE[3]:
                //biannually
                return TimeStampHelper::incrementInDate($incStartDate, ' +6 months') . " 00:00:00";
                break;
            default:
                //annually
                return TimeStampHelper::incrementInDate($incStartDate, ' +1 year') . " 00:00:00";
        }
    }
}

if (!function_exists('returnIfSerialize')) {
    function returnIfSerialize($str)
    {
        $data = @unserialize($str);
        if ($data !== false) {
            return $data;
        } else {
            return false;
        }
    }
}

function getIds($value)
{
    return array_map('intval', explode(',', $value));
}

function pageLimit($request): int
{
    return ($request->filled('page_limit') && is_numeric($request->input('page_limit'))) ? $request->input('page_limit') : env('PAGE_LIMIT', 20);
}

function stringToUpper($string)
{
    return trim(strtoupper($string));
}