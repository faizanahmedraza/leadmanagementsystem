<?php

namespace App\Helpers;

use \DateTime;

use Carbon\Carbon;

class TimeStampHelper
{
    /**
     * Validate timestamp expiry
     *
     * @param DateTime
     *
     * @return boolean
     */
    public static function expiryValidation(\DateTime $expiryDate)
    {
        $currentDate = new \DateTime("now");

        if ($currentDate > $expiryDate) {
            return false;
        }
        return true;
    }

    /**
     * count difference in days
     *
     * @param earlierDate
     * @param futureDate
     *
     * @return int
     */
    public static function countDaysBetween(DateTime $earlierDate, DateTime $futureDate): int
    {
        $earlierDate = new DateTime($earlierDate->format('Y-m-d'));
        $futureDate = new DateTime($futureDate->format('Y-m-d'));

        $days = $futureDate->diff($earlierDate)->format("%a");
        if ($days > 31) {
            $days = 30;
        }

        return $days;
    }

    /**
     * Get current month days
     *
     *
     * @return int
     */
    public static function getCurrentMonthDays(): int
    {
        $date = new DateTime('last day of this month');
        return $date->format('d');
    }


    /**
     * Get first Date of next month
     *
     *
     * @return string
     */
    public static function getNextMonthSameDate($day = 'd'): String
    {
        $date = new DateTime();
        self::addMonths($date, 1);
        return $date->format('Y-m-' . $day);
    }


    /**
     *  update month
     *
     *
     * @return string
     */
    public static function updateMonth($date = 'now', $months, $day = 'd'): String
    {
        $date = new DateTime($date);
        self::addMonths($date, $months);
        return $date->format('Y-m-' . $day);
    }


    /**
     * Get Current Date
     *
     *
     * @return string
     */
    public static function getCurrentDate($day = 'd'): String
    {
        $date = new DateTime();
        return $date->format('Y-m-' . $day);
    }


    /**
     * Get Current Date
     *
     *
     * @return string
     */
    public static function formate($date, $formate): String
    {
        return ($date) ? date($formate, strtotime($date)) : '';
    }

    public static function now(): String
    {
        return (new \DateTime("now"))->format("Y-m-d H:i:s");
    }


    /**
     * Get Date
     *
     *
     * @return string
     */
    public static function getDate($days, $date = null, $formate = 'Y-m-d H:i:s'): String
    {
        $date = ($date) ? new \DateTime($date) : new DateTime();
        $date->modify("{$days} day");
        return $date->format($formate);
    }

    /**
     *  Get Current Timestamp
     */
    public static function currentTimestamp()
    {
        $date = new DateTime();
        return $date->getTimestamp();
    }

    /**
     * Get Hours
     *
     *
     * @return string
     */
    public static function getHours($hours): String
    {
        $date = new DateTime();
        $date->modify("{$hours} hour");
        return $date->format('Y-m-d H:i:s');
    }


    /**
     * count month difference between two days
     *
     *
     * @return string
     */
    public static function countMonthBetween(DateTime $from, DateTime $to): String
    {
        $from = new DateTime($from->format('Y-m-d'));
        $to = new DateTime($to->format('Y-m-d'));

        $interval = $from->diff($to);
        return $interval->m;
    }


    /**
     * Get Date of custom month
     *
     *
     * @return string
     */
    public static function getCustomMonthDate($month): String
    {
        $date = new DateTime("{$month} month");
        return $date->format('Y-m-d H:i:s');
    }

    public static function getTimestampByFormat($format = 'Y-m-d H:i:s')
    {
        $date = new DateTime();
        return $date->format($format);
    }

    public static function currentDate()
    {
        $date = new DateTime();
        return $date->format('Y-m-d');
    }

    /**
     * Formate Given Date
     */
    public static function formateDate($date)
    {
        $date = (new DateTime($date))->format('Y-m-d');
        return $date;
    }

    /**
     * count difference in days
     *
     * @param earlierDate
     * @param futureDate
     *
     * @return int
     */
    public static function countAccurateDays(String $earlierDate, String $futureDate): int
    {
        $earlierDate = new DateTime(date('Y-m-d', strtotime($earlierDate)));
        $futureDate = new DateTime(date('Y-m-d', strtotime($futureDate)));
        return $futureDate->diff($earlierDate)->days;
    }

    /**
     *  Get first date of any month
     */
    public static function getFirstDate($date, $month = null)
    {
        $date = new DateTime($date);
        if ($month) {
            $date->modify("{$month} month");
        }
        return $date->format('Y-m-01');
    }


    /**
     *  Get first date of any month
     */
    public static function getEndDate($date)
    {
        $date = new DateTime($date);
        return $date->format('Y-m-t');
    }

    /**
     *  Get list of month between two dates
     */
    public static function generateMonthsBetweenDates($startDate, $endDate)
    {
        $interval = \DateInterval::createFromDateString('1 month');
        return new \DatePeriod(new \DateTime($startDate), $interval, new \DateTime($endDate));
    }

    public static function countDays(DateTime $earlierDate, DateTime $futureDate): int
    {
        $earlierDate = new DateTime($earlierDate->format('Y-m-d'));
        $futureDate = new DateTime($futureDate->format('Y-m-d'));

        return $futureDate->diff($earlierDate)->format("%a");
    }

    /**
     *  Get Month name from number
     */
    public static function getMonthName($month, $formate = 'F')
    {
        $date = DateTime::createFromFormat('!m', $month);
        return strtoupper($date->format($formate));
    }

    /**
     *  Get start and end dates of month by numner
     */
    public static function getDateOfMonth($month, $year)
    {
        $dates = [];
        $first = date('01-' . $month . '-' . $year);
        $last = date(date('t', strtotime($first)) . '-' . $month . '-' . $year);
        return (object)['first' => date('Y-m-d 23:59:59', strtotime($first)), 'last' => date('Y-m-d 23:59:59', strtotime($last))];
    }

    /**
     * count difference in hour
     *
     * @param earlierDate
     * @param futureDate
     *
     * @return int
     */
    public static function calculateDateDiff(String $earlierDate, String $futureDate)
    {
        $earlierDate = Carbon::createFromFormat('Y-m-d H:i:s', $earlierDate);
        $futureDate = Carbon::createFromFormat('Y-m-d H:i:s', $futureDate);
        return (object)[
            'hours' => $earlierDate->diffInHours($futureDate),
            'minus' => (int)$earlierDate->diff($futureDate)->format('%I')
        ];
    }

    public static function getMonthDay($date = 'now')
    {
        $date = new DateTime($date);
        return $date->format('d');
    }

    public static function addMonths($date, $months)
    {
        $init = clone $date;
        $modifier = $months . ' months';
        $back_modifier = -$months . ' months';

        $date->modify($modifier);
        $back_to_init = clone $date;
        $back_to_init->modify($back_modifier);

        while ($init->format('m') != $back_to_init->format('m')) {
            $date->modify('-1 day');
            $back_to_init = clone $date;
            $back_to_init->modify($back_modifier);
        }
    }

    /**
     *  Get first date of any month
     */
    public static function getFistDate($date, $month = null)
    {
        $date = new DateTime($date);
        if ($month) {
            $date->modify("{$month} month");
        }
        return $date->format('Y-m-01');
    }

    public static function incrementInDate($date, $increment = " +1 week")
    {
        return date('Y-m-d', strtotime($date . $increment));
    }
}
