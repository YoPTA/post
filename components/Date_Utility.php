<?php

/*
 * ¬спомогательный класс, помогающий при работе с датой.
 *
 * ƒќ–јЅј“џ¬ј≈“—я
 */

class Date_Utility
{

    private $system_format_date;
    private $system_format_time;
    private $system_format_datetime;


    public function __construct()
    {
        $this->system_format_date = 'd.m.Y';
        $this->system_format_time = 'H:i:s';
        $this->system_format_datetime = $this->system_format_date.' '.$this->system_format_time;

    }

    public function getDateTime($value = null, $format_out = null, $format_in = null)
    {
        if (isset($value))
        {
            $format_in = (isset($format_in)) ? $format_in : $this->system_format_datetime;

            $datetime = DateTime::createFromFormat($format_in, $value);

            if ($datetime === false)
            {
                return false;
            }

            if ($datetime->format($format_in) != $value)
            {
                return false;
            }
        }else{
            $datetime = new DateTime();
        }
        return $datetime->format(((isset($format_out)) ? $format_out : system_format_datetime));
    }

    function PlainGetDate($value = null, $format_out = null, $format_in = null)
    {
        global $SYSTEM_FORMAT_DATE;
        global $SYSTEM_FORMAT_DATETIME;

        return PlainGetDateTime($value, ((isset($format_out)) ? $format_out : $SYSTEM_FORMAT_DATE), ((isset($format_in)) ? $format_in : $SYSTEM_FORMAT_DATETIME));
    }

    function PlainGetTime($value = null, $format_out = null, $format_in = null)
    {
        global $SYSTEM_FORMAT_TIME;
        global $SYSTEM_FORMAT_DATETIME;

        return PlainGetDateTime($value, ((isset($format_out)) ? $format_out : $SYSTEM_FORMAT_TIME), ((isset($format_in)) ? $format_in : $SYSTEM_FORMAT_DATETIME));
    }

    function PlainDateTimeTo($value, $format_out, $format_in = null)
    {
        global $SYSTEM_FORMAT_DATETIME;

        if ($value == '')
        {
            return false;
        }

        return PlainGetDateTime($value, $format_out, ((isset($format_in)) ? $format_in : $SYSTEM_FORMAT_DATETIME));
    }

    function PlainDateTo($value, $format_out, $format_in = null)
    {
        global $SYSTEM_FORMAT_DATE;

        if ($value == '')
        {
            return false;
        }

        return PlainGetDate($value, $format_out, ((isset($format_in)) ? $format_in : $SYSTEM_FORMAT_DATE));
    }

    function PlainTimeTo($value, $format_out, $format_in = null)
    {
        global $SYSTEM_FORMAT_TIME;

        if ($value == '')
        {
            return false;
        }

        return PlainGetTime($value, $format_out, ((isset($format_in)) ? $format_in : $SYSTEM_FORMAT_TIME));
    }

    function PlainDateTimeToSQLDateTime($value)
    {
        return PlainDateTimeTo($value, 'Y-m-d H:i:s');
    }

    function PlainDateToSQLDate($value)
    {
        return PlainDateTo($value, 'Y-m-d');
    }

    function PlainTimeToSQLTime($value)
    {
        return PlainTimeTo($value, 'H:i:s');
    }

    /*function PlainSQLDateTimeToDateTime($value)
    {
        return PlainDateTimeTo($value, $SYSTEM_FORMAT_DATETIME, 'Y-m-d H:i:s');
    }

    function PlainSQLDateToDate($value)
    {
        return PlainDateTo($value, $SYSTEM_FORMAT_DATE, 'Y-m-d');
    }

    function PlainSQLTimeToTime($value)
    {
        return PlainTimeTo($value, $SYSTEM_FORMAT_TIME, 'H:i:s');
    }*/

    function PlainTimeToShortTime($value)
    {
        return PlainTimeTo($value, 'H:i');
    }

    function PlainCheckDateTime($value, $format = null)
    {
        global $SYSTEM_FORMAT_DATETIME;

        $format = (isset($format)) ? $format : $SYSTEM_FORMAT_DATETIME;

        $datetime = DateTime::createFromFormat($format, $value);
        if ($datetime === false)
        {
            return false;
        }
        return ($datetime->format($format) == $value) ? true : false;
    }

    function PlainCheckDate($value, $format = null)
    {
        global $SYSTEM_FORMAT_DATE;

        return PlainCheckDateTime($value, ((isset($format)) ? $format : $SYSTEM_FORMAT_DATE));
    }

    function PlainCheckTime($value, $format = null)
    {
        global $SYSTEM_FORMAT_TIME;

        return PlainCheckDateTime($value, ((isset($format)) ? $format : $SYSTEM_FORMAT_TIME));
    }

    function PlainDateTimeToDate($value)
    {
        global $SYSTEM_FORMAT_DATE;

        return PlainDateTimeTo($value, $SYSTEM_FORMAT_DATE);
    }

    function PlainDateTimeToTime($value)
    {
        global $SYSTEM_FORMAT_TIME;

        return PlainDateTimeTo($value, $SYSTEM_FORMAT_TIME);
    }

    function PlainDateToDateTime($value, $time = '00:00:00')
    {
        global $SYSTEM_FORMAT_DATE;

        return PlainDateTo($value, $SYSTEM_FORMAT_DATE.' '.$time);
    }

    function PlainTimeToDateTime($value, $date = '01.01.2001')
    {
        global $SYSTEM_FORMAT_TIME;

        return PlainTimeTo($value, $date.' '.$SYSTEM_FORMAT_TIME);
    }

    function PlainDateToDateTimeBegin($value)
    {
        return PlainDateToDateTime($value);
    }

    function PlainDateToDateTimeEnd($value)
    {
        return PlainDateToDateTime($value, '23:59:59');
    }

    function PlainDateToDateBegin($value, $month = false)
    {
        return PlainDateTo($value, '01.'.(($month) ? '01' : 'm').'.Y');
    }

    function PlainDateToDateEnd($value, $month = false)
    {
        return PlainDateTo($value, 't.'.(($month) ? '12' : 'm').'.Y');
    }

    function PlainChangeDateTime($value, $inc, $interval, $format = null)
    {
        global $SYSTEM_FORMAT_DATETIME;

        $format = (isset($format)) ? $format : $SYSTEM_FORMAT_DATETIME;

        $datetime = DateTime::createFromFormat($format, $value);
        if ($datetime === false)
        {
            return false;
        }
        if ($datetime->format($format) != $value)
        {
            return false;
        }

        $dateinterval = new DateInterval('P'.$interval);

        if ($inc)
        {
            $datetime->add($dateinterval);
        }else{
            $datetime->sub($dateinterval);
        }
        return $datetime->format($format);
    }

    function PlainChangeDate($value, $inc, $interval, $format = null)
    {
        global $SYSTEM_FORMAT_DATE;

        return PlainChangeDateTime($value, $inc, $interval, ((isset($format)) ? $format : $SYSTEM_FORMAT_DATE));
    }

    function PlainChangeTime($value, $inc, $interval, $format = null)
    {
        global $SYSTEM_FORMAT_TIME;

        return PlainChangeDateTime($value, $inc, $interval, ((isset($format)) ? $format : $SYSTEM_FORMAT_TIME));
    }

    function PlainIncDateTimeYear($value, $count = 1)
    {
        return PlainChangeDateTime($value, true, abs($count).'Y');
    }

    function PlainIncDateYear($value, $count = 1)
    {
        return PlainChangeDate($value, true, abs($count).'Y');
    }

    function PlainDecDateTimeYear($value, $count = 1)
    {
        return PlainChangeDateTime($value, false, abs($count).'Y');
    }

    function PlainDecDateYear($value, $count = 1)
    {
        return PlainChangeDate($value, false, abs($count).'Y');
    }

    function PlainIncDateTimeMonth($value, $count = 1)
    {
        return PlainChangeDateTime($value, true, abs($count).'M');
    }

    function PlainIncDateMonth($value, $count = 1)
    {
        return PlainChangeDate($value, true, abs($count).'M');
    }

    function PlainDecDateTimeMonth($value, $count = 1)
    {
        return PlainChangeDateTime($value, false, abs($count).'M');
    }

    function PlainDecDateMonth($value, $count = 1)
    {
        return PlainChangeDate($value, false, abs($count).'M');
    }

    function PlainIncDateTimeDay($value, $count = 1)
    {
        return PlainChangeDateTime($value, true, abs($count).'D');
    }

    function PlainIncDateDay($value, $count = 1)
    {
        return PlainChangeDate($value, true, abs($count).'D');
    }

    function PlainDecDateTimeDay($value, $count = 1)
    {
        return PlainChangeDateTime($value, false, abs($count).'D');
    }

    function PlainDecDateDay($value, $count = 1)
    {
        return PlainChangeDate($value, false, abs($count).'D');
    }

    function PlainIncDateTimeHour($value, $count = 1)
    {
        return PlainChangeDateTime($value, true, 'T'.abs($count).'H');
    }

    function PlainIncTimeHour($value, $count = 1)
    {
        return PlainChangeTime($value, true, 'T'.abs($count).'H');
    }

    function PlainDecDateTimeHour($value, $count = 1)
    {
        return PlainChangeDateTime($value, false, 'T'.abs($count).'H');
    }

    function PlainDecTimeHour($value, $count = 1)
    {
        return PlainChangeTime($value, false, 'T'.abs($count).'H');
    }

    function PlainIncDateTimeMinute($value, $count = 1)
    {
        return PlainChangeDateTime($value, true, 'T'.abs($count).'M');
    }

    function PlainIncTimeMinute($value, $count = 1)
    {
        return PlainChangeTime($value, true, 'T'.abs($count).'M');
    }

    function PlainDecDateTimeMinute($value, $count = 1)
    {
        return PlainChangeDateTime($value, false, 'T'.abs($count).'M');
    }

    function PlainDecTimeMinute($value, $count = 1)
    {
        return PlainChangeTime($value, false, 'T'.abs($count).'M');
    }

    function PlainIncDateTimeSecond($value, $count = 1)
    {
        return PlainChangeDateTime($value, true, 'T'.abs($count).'S');
    }

    function PlainIncTimeSecond($value, $count = 1)
    {
        return PlainChangeTime($value, true, 'T'.abs($count).'S');
    }

    function PlainDecDateTimeSecond($value, $count = 1)
    {
        return PlainChangeDateTime($value, false, 'T'.abs($count).'S');
    }

    function PlainDecTimeSecond($value, $count = 1)
    {
        return PlainChangeTime($value, false, 'T'.abs($count).'S');
    }

    function PlainDiffDateTime($value1, $value2, $absolute = true, $format = null)
    {
        global $SYSTEM_FORMAT_DATETIME;

        $format = (isset($format)) ? $format : $SYSTEM_FORMAT_DATETIME;

        $datetime1 = DateTime::createFromFormat($format, $value1);
        if ($datetime1 === false)
        {
            return false;
        }
        if ($datetime1->format($format) != $value1)
        {
            return false;
        }

        $datetime2 = DateTime::createFromFormat($format, $value2);
        if ($datetime2 === false)
        {
            return false;
        }
        if ($datetime2->format($format) != $value2)
        {
            return false;
        }

        $interval = $datetime2->diff($datetime1, $absolute);

        return ($interval->s + ($interval->i * 60) + ($interval->h * 3600) + ($interval->days * 86400)) * (($interval->invert == 1) ? -1 : 1);
    }

    function PlainDiffDate($value1, $value2, $absolute = true, $format = null)
    {
        global $SYSTEM_FORMAT_DATE;

        return PlainDiffDateTime($value1, $value2, $absolute, ((isset($format)) ? $format : $SYSTEM_FORMAT_DATE));
    }

    function PlainDiffTime($value1, $value2, $absolute = true, $format = null)
    {
        global $SYSTEM_FORMAT_TIME;

        return PlainDiffDateTime($value1, $value2, $absolute, ((isset($format)) ? $format : $SYSTEM_FORMAT_TIME));
    }

    function PlainGetTimeInterval($value, $absolute = true)
    {
        $result = array();

        $d = 0;
        $h = 0;
        $m = 0;
        $s = 0;

        if ($absolute)
        {
            $value = abs($value);
        }

        $factor = ($value < 0) ? -1 : 1;

        $value = abs($value);

        if ($value >= 86400)
        {
            $d = floor($value / 86400);
            $value = $value - ($d * 86400);
        }

        if ($value >= 3600)
        {
            $h = floor($value / 3600);
            $value = $value - ($h * 3600);
        }

        if ($value >= 60)
        {
            $m = floor($value / 60);
            $value = $value - ($m * 60);
        }

        $s = $value;

        $result[0] = $s * $factor;
        $result[1] = $m * $factor;
        $result[2] = $h * $factor;
        $result[3] = $d * $factor;

        return $result;
    }

    function PlainGetTimeIntervalDay($value, $absolute = true)
    {
        $timeinterval = PlainGetTimeInterval($value, $absolute);
        return $timeinterval[3];
    }

    function PlainGetTimeIntervalHour($value, $first_null = true, $absolute = true)
    {
        $timeinterval = PlainGetTimeInterval($value, $absolute);

        return ($first_null) ? sprintf('%02d', $timeinterval[2]) : $timeinterval[2];
    }

    function PlainGetTimeIntervalMinute($value, $first_null = true, $absolute = true)
    {
        $timeinterval = PlainGetTimeInterval($value, $absolute);

        return ($first_null) ? sprintf('%02d', $timeinterval[1]) : $timeinterval[1];
    }

    function PlainGetTimeIntervalSecond($value, $first_null = true, $absolute = true)
    {
        $timeinterval = PlainGetTimeInterval($value, $absolute);

        return ($first_null) ? sprintf('%02d', $timeinterval[0]) : $timeinterval[0];
    }

    function PlainCompareDateTime($value1, $value2, $format = null)
    {
        global $SYSTEM_FORMAT_DATETIME;

        $format = (isset($format)) ? $format : $SYSTEM_FORMAT_DATETIME;

        $datetime1 = DateTime::createFromFormat($format, $value1);
        if ($datetime1 === false)
        {
            return false;
        }
        if ($datetime1->format($format) != $value1)
        {
            return false;
        }

        $datetime2 = DateTime::createFromFormat($format, $value2);
        if ($datetime2 === false)
        {
            return false;
        }
        if ($datetime2->format($format) != $value2)
        {
            return false;
        }

        return ($datetime1 == $datetime2) ? 0 : (($datetime1 > $datetime2) ? 1 : -1);
    }

    function PlainCompareDate($value1, $value2, $format = null)
    {
        global $SYSTEM_FORMAT_DATE;

        return PlainCompareDateTime($value1, $value2, ((isset($format)) ? $format : $SYSTEM_FORMAT_DATE));
    }

    function PlainCompareTime($value1, $value2, $format = null)
    {
        global $SYSTEM_FORMAT_TIME;

        return PlainCompareDateTime($value1, $value2, ((isset($format)) ? $format : $SYSTEM_FORMAT_TIME));
    }

    function PlainDecodeDateTime($value, $format = null)
    {
        global $SYSTEM_FORMAT_DATETIME;

        $format = (isset($format)) ? $format : $SYSTEM_FORMAT_DATETIME;

        $datetime = DateTime::createFromFormat($format, $value);
        if ($datetime === false)
        {
            return false;
        }
        if ($datetime->format($format) != $value)
        {
            return false;
        }

        $result = array();

        $result[0] = (integer)$datetime->format('Y');
        $result[1] = (integer)$datetime->format('n');
        $result[2] = (integer)$datetime->format('j');
        $result[3] = (integer)$datetime->format('G');
        $result[4] = (integer)$datetime->format('i');
        $result[5] = (integer)$datetime->format('s');
        $result[6] = (integer)$datetime->format('w');
        $result[7] = (integer)$datetime->format('t');

        return $result;
    }

    function PlainDecodeDate($value, $format = null)
    {
        global $SYSTEM_FORMAT_DATE;

        return PlainDecodeDateTime($value, ((isset($format)) ? $format : $SYSTEM_FORMAT_DATE));
    }

    function PlainDecodeTime($value, $format = null)
    {
        global $SYSTEM_FORMAT_TIME;

        return PlainDecodeDateTime($value, ((isset($format)) ? $format : $SYSTEM_FORMAT_TIME));
    }

    function PlainGetDecodeYear($value)
    {
        return $value[0];
    }

    function PlainGetDecodeMonth($value)
    {
        return $value[1];
    }

    function PlainGetDecodeDay($value)
    {
        return $value[2];
    }

    function PlainGetDecodeHour($value)
    {
        return $value[3];
    }

    function PlainGetDecodeMinute($value)
    {
        return $value[4];
    }

    function PlainGetDecodeSecond($value)
    {
        return $value[5];
    }

    function PlainGetDecodeWeekDay($value)
    {
        return $value[6];
    }

    function PlainGetDecodeLastDay($value)
    {
        return $value[7];
    }

    function PlainEncodeDateTime($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $format = null)
    {
        global $SYSTEM_FORMAT_DATETIME;

        $value = sprintf('%02d', (($day == null) ? 1 :  $day)).'.'.sprintf('%02d', (($month == null) ? 1 : $month)).'.'.sprintf('%04d', (($year == null) ? 2000 : $year)).' '.sprintf('%02d', (($hour == null) ? 0 : $hour)).':'.sprintf('%02d', (($minute == null) ? 0 : $minute)).':'.sprintf('%02d', (($second == null) ? 0 : $second));

        $datetime = DateTime::createFromFormat($SYSTEM_FORMAT_DATETIME, $value);
        if ($datetime === false)
        {
            return false;
        }

        if ($datetime->format($SYSTEM_FORMAT_DATETIME) != $value)
        {
            return false;
        }

        return $datetime->format(((isset($format)) ? $format : $SYSTEM_FORMAT_DATETIME));
    }

    function PlainEncodeDate($year = null, $month = null, $day = null, $format = null)
    {
        global $SYSTEM_FORMAT_DATE;

        return PlainEncodeDateTime($year, $month, $day, null, null, null, ((isset($format)) ? $format : $SYSTEM_FORMAT_DATE));
    }

    function PlainEncodeTime($hour = null, $minute = null, $second = null, $format = null)
    {
        global $SYSTEM_FORMAT_TIME;

        return PlainEncodeDateTime(null, null, null, $hour, $minute, $second, ((isset($format)) ? $format : $SYSTEM_FORMAT_TIME));
    }
}