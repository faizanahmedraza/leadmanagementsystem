<?php

namespace App\Helpers;

use Faker\Factory as Faker;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class GeneralHelper
{
    /**
     * Generate Random String
     *
     * @param $regex String requires
     *
     * @return String
     */
    public static function stringGenerate($regex): String
    {
        $faker = Faker::create();
        return $faker->regexify($regex);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public static function paginate($list, $pagination, $pageLimit)
    {
        $pagintion = (empty($pagination) || !isset($pagination->total)) ? (object)[
                'total' => 0,
                'per_page' => $pageLimit,
                'current_page' => 1,
            ]:  $pagination ;
    
        $items = $list instanceof Collection ? $list : Collection::make($list);
        return new LengthAwarePaginator($items, $pagintion->total, $pagintion->per_page, $pagintion->current_page, []);
    }

    public static function explode($value)
    {
        $value = strtolower($value);

        if (strpos($value, ',') !== false) {
            return explode(",", $value);
        }

        return $value;
    }
}
