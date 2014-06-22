<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Date;

/**
 *
 *
 *日期操作类,提供常用的日期操作方法
 *1.>=php5.3.5以上
 *2. pdo
 *3.有php基础，熟悉面向对象编程
 *
 */
class Date
{


    /**
     * 获取指定时间戳的0点
     * @param int $t 时间戳
     * @return int 指定时间戳的0点
     */
    public static function getAM12($t)
    {
        $year = date("Y", $t);
        $month = date('m', $t);
        $d = date("d", $t);
        return mktime(0, 0, 0, $month, $d, $year);
    }


    /**
     * 获取指定时间戳所在周的开始时间和结束时间
     * @param int $ts 时间戳
     * @param int $startWeekday 一周开始的weekday，默认为周天(7)作为一周开始
     * @return array 一周的开始时间戳和结束时间戳 array(starttime,endtime)
     */
    public static function aWeek($ts, $startWeekday = 7)
    {
        $time = self::getAM12($ts); // 回到0点
        $weekOfDay = date("w", $time); // 获取当前时间是一周的第几天
        $weekOfDay = $weekOfDay == 0 ? 7 : $startWeekday; // 先按照7天正常周
        if ($weekOfDay == $startWeekday)
            $weekOfDay = 0; // 一周开始
        if ($weekOfDay == 0) {
            // 给定的时间就是一周开始
            $weekOfFistDay = $time;
        } else {
            if ($weekOfDay < $startWeekday) {
                $weekOfFistDay = strtotime("-7day", $time); // 回到上周
                $weekOfFistDay = strtotime("+" . ($startWeekday - $weekOfDay) . "day", $weekOfFistDay); // 回到本周开始的的时间戳
            } else {
                $weekOfFistDay = strtotime("-" . ($weekOfDay - $startWeekday) . "day", $time); // 回到本周开始的的时间戳
            }
        }
        $weekOfEndDay = strtotime("+7day", $weekOfFistDay) - 1; // 本周最后一天23:59:59

        return array(
            $weekOfFistDay,
            $weekOfEndDay
        );
    }

    /**
     *
     * 是否在同一周
     * @param int $ts1
     * @param int $ts2
     * @param int $startWeekday 一周开始的weekday,默认为7(周天)
     * @return bool true在同一周 false不在同一周
     */
    public static function isSameWeek($ts1, $ts2, $startWeekday = 7)
    {
        return (self::aWeek($ts1, $startWeekday) == self::aWeek($ts2, $startWeekday));
    }


    /**
     * 是否在同一年
     * @param $ts1
     * @param $ts2
     * @return bool
     */
    public static function isSameYear($ts1, $ts2)
    {
        return intval(date("Y", $ts1)) == intval(date("Y", $ts2));
    }

    /**
     * 是否在同一月
     * @param int $ts1
     * @param int $ts2
     * @return bool
     */
    public static function isSameMonth($ts1, $ts2)
    {
        return strcmp(date("Y-m", $ts1), date("Y-m", $ts2)) === 0;
    }

    /**
     * 是否在同一天
     * @param int $ts1
     * @param int $ts2
     * @return bool
     */
    public static function isSameDay($ts1, $ts2)
    {
        return strcmp(date("Y-m-d", $ts1), date("Y-m-d", $ts2)) === 0;
    }


    /**
     * 获取当前时间的年和周，注意：如果周跨年，下周年的头几天会算到上一年的最后一天
     * @param int $ts
     * @param int $startDay
     * @return string 年周 例如：201425
     */
    public static function getWeekOfYear($ts, $startDay = 7)
    {
        $year = intval(date("Y", $ts)); // 获取年
        $limit = self::aWeek($ts, $startDay);
        $startLimit = $limit [0];
        $maxWeek = 60; //假定一年最多60周
        for ($i = 1; $i <= $maxWeek; $i++) {
            $day = $i * 7;
            $pre = strtotime("-{$day}day", $startLimit);
            if (self::isSameYear($ts, $pre) == false) {
                if ($i == 1) {
                    $w = date("w", mktime(0, 0, 0, 1, 1, $year));
                    if (($w == 0 && $startDay == 7) && $w == $startDay) {
                        // 当年的1月1日就是一周的开始
                        return $year . $i;
                    } else {
                        // 不是一周的开始,算到下一年
                        break;
                    }
                }
                return $year . $i;
            }
        }
        $ret = self::getWeekOfYear(mktime(0, 0, 0, 12, 31, intval($year) - 1), $startDay); // 上一年的最后一周
        return $ret;
    }

    /**
     * 返回当前时间戳，对time函数的包装
     * @return int
     *
     */
    public static function now()
    {
        return time();
    }

}