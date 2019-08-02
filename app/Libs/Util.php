<?php

namespace App\Libs;

use Illuminate\Support\Facades\Log;

class Util
{
    /**
     * judge current time whether it's between 23:20 and 8:00
     *
     * @param string $now made by function date('xx:xx:xx')
     * @return bool
     */
    public static function isMidnight(string $now)
    {
        $nowTimestamp = strtotime($now);
        $startMidnight = strtotime(date(env('DO_NOT_DISTURB_FROM', '23:20:00')));
        $endMidnight = strtotime(date(env('DO_NOT_DISTURB_TO', '08:00:00')));

        return $startMidnight < $nowTimestamp || $nowTimestamp < $endMidnight;
    }

    /**
     * judge a number whether it's even number
     *
     * @param string $minuteStr
     * @return bool
     * @throws \Exception
     */
    public static function isEvenNumber(string $minuteStr)
    {
        $tenPlaceMinute = intval(substr($minuteStr, 0, 1));
        switch ($tenPlaceMinute) {
            case 0 :
            case 2 :
            case 4 :
                return true;
            case 1 :
            case 3 :
            case 5 :
                return false;
        }

        throw new \Exception('This is not between 0 and 6. Number is ' . $minuteStr);
    }

    /**
     * @param bool $isRunFromCli
     * @param null $globalVarServer
     * @return string
     */
    public function getSentType(bool $isRunFromCli, $globalVarServer = null)
    {
        if ($isRunFromCli) {
//            return $globalVarServer['argv'][1];
            return 'sent_01';
        }

        Log::info('run not from cli');
        return 'sent_01';
    }
}