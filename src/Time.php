<?php

namespace yshop;

class Time
{
    /**
     * 返回今日开始和结束的时间戳
     * @return array
     */
    public static function today()
    {
        [$y, $m, $d] = explode('-', date('Y-m-d'));
        return [
            mktime(0, 0, 0, $m, $d, $y),
            mktime(23, 59, 59, $m, $d, $y),
        ];
    }

    /**
     * 返回昨日开始和结束的时间戳
     * @return array
     */
    public static function yesterday()
    {
        $yesterday = date('d') - 1;
        return [
            mktime(0, 0, 0, date('m'), $yesterday, date('Y')),
            mktime(23, 59, 59, date('m'), $yesterday, date('Y')),
        ];
    }

    /**
     * 返回本周开始和结束的时间戳
     * @return array
     */
    public static function week()
    {
        [$y, $m, $d, $w] = explode('-', date('Y-m-d-w'));
        if ($w == 0) $w = 7; //修正周日的问题
        return [
            mktime(0, 0, 0, $m, $d - $w + 1, $y), mktime(23, 59, 59, $m, $d - $w + 7, $y),
        ];
    }

    /**
     * 返回上周开始和结束的时间戳
     * @return array
     */
    public static function lastWeek()
    {
        $timestamp = time();
        return [
            strtotime(date('Y-m-d', strtotime("last week Monday", $timestamp))),
            strtotime(date('Y-m-d', strtotime("last week Sunday", $timestamp))) + 24 * 3600 - 1,
        ];
    }

    /**
     * 返回本月开始和结束的时间戳
     * @return array
     */
    public static function month()
    {
        [$y, $m, $t] = explode('-', date('Y-m-t'));
        return [
            mktime(0, 0, 0, $m, 1, $y),
            mktime(23, 59, 59, $m, $t, $y),
        ];
    }

    /**
     * 返回本月的天数
     * @return float
     */
    public static function daysMonth()
    {
        [$begin, $end] = self::month();
        $a_dt = getdate($begin);
        $b_dt = getdate($end);
        $a_new = mktime(12, 0, 0, $a_dt['mon'], $a_dt['mday'], $a_dt['year']);
        $b_new = mktime(12, 0, 0, $b_dt['mon'], $b_dt['mday'], $b_dt['year']);

        return round(abs($a_new - $b_new) / 86400);
    }

    /**
     * 返回上个月开始和结束的时间戳
     * @return array
     */
    public static function lastMonth()
    {
        $y = date('Y');
        $m = date('m');
        $begin = mktime(0, 0, 0, $m - 1, 1, $y);
        $end = mktime(23, 59, 59, $m - 1, date('t', $begin), $y);

        return [$begin, $end];
    }

    /**
     * 返回今年开始和结束的时间戳
     * @return array
     */
    public static function year()
    {
        $y = date('Y');
        return [
            mktime(0, 0, 0, 1, 1, $y),
            mktime(23, 59, 59, 12, 31, $y),
        ];
    }

    /**
     * 返回去年开始和结束的时间戳
     * @return array
     */
    public static function lastYear()
    {
        $year = date('Y') - 1;
        return [
            mktime(0, 0, 0, 1, 1, $year),
            mktime(23, 59, 59, 12, 31, $year),
        ];
    }

    /**
     * 获取几天前零点到现在/昨日结束的时间戳
     * @param int  $day 天数
     * @param bool $now 返回现在或者昨天结束时间戳
     * @return array
     */
    public static function dayToNow($day = 1, $now = true)
    {
        $end = time();
        if (!$now) {
            [$foo, $end] = self::yesterday();
        }

        return [
            mktime(0, 0, 0, date('m'), date('d') - $day, date('Y')),
            $end,
        ];
    }

    /**
     * 返回几天前的时间戳
     * @param int $day
     * @return int
     */
    public static function daysAgo($day = 1)
    {
        $nowTime = time();
        return $nowTime - self::daysToSecond($day);
    }

    /**
     * 返回几天后的时间戳
     * @param int $day
     * @return int
     */
    public static function daysAfter($day = 1)
    {
        $nowTime = time();
        return $nowTime + self::daysToSecond($day);
    }

    /**
     * 天数转换成秒数
     * @param int $day
     * @return int
     */
    public static function daysToSecond($day = 1)
    {
        return $day * 86400;
    }

    /**
     * 周数转换成秒数
     * @param int $week
     * @return int
     */
    public static function weekToSecond($week = 1)
    {
        return self::daysToSecond() * 7 * $week;
    }

    /**
     * 计算二个时间戳的相差天数
     * @param int|string $begin
     * @param int|string $end
     * @return float
     */
    public static function days($begin, $end)
    {
        $a_dt = getdate(ctype_digit($begin) ? (int)$begin : strtotime($begin));
        $b_dt = getdate(ctype_digit($end) ? (int)$end : strtotime($end));
        $a_new = mktime(12, 0, 0, $a_dt['mon'], $a_dt['mday'], $a_dt['year']);
        $b_new = mktime(12, 0, 0, $b_dt['mon'], $b_dt['mday'], $b_dt['year']);

        return round(abs($a_new - $b_new) / 86400);
    }
}
