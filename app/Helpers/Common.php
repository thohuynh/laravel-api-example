<?php

namespace App\Helpers;

use Carbon\Carbon;
use Exception;
use Arr;
use Str;

/**
 * Class Common.
 *
 * @package App\Helpers
 */
class Common
{
    /**
     * @var int $pagination default limit.
     */
    public static $paginationLimit = 10;

    /**
     * Check is date time string by format.
     *
     * @param $format
     * @param $value
     * @return bool
     */
    public static function isDateTimeString($format, $value)
    {
        try {
            $checked = true;
            Carbon::createFromFormat($format, $value);
        } catch (Exception $error) {
            $checked = false;
        }

        return $checked;
    }

    public static function getInt($value)
    {
        return intval(str_replace(",", "", $value));
    }

    public static function getValueByKeyArray($array, $arrayKey)
    {
        return  Arr::where($array, function ($value, $key) use ($arrayKey) {
            return in_array($key, $arrayKey);
        });
    }

    /**
     * Generate token
     *
     * @param int $length
     * @return string
     */
    public static function generateToken($length = 51)
    {
        return uniqid(Str::random($length));
    }

    /**
     * Check time has expired.
     * @param $time
     * @return bool
     */
    public static function hasExpired($time)
    {
        return Carbon::parse($time)->lessThan(Carbon::now());
    }
}
