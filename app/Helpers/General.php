<?php

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

if (!function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string $path
     * @return string
     */
    function public_path($path = '')
    {
        return env('PUBLIC_PATH', base_path('public')) . ($path ? '/' . $path : $path);
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