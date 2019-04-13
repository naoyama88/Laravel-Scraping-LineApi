<?php

namespace App\Libs;

use Illuminate\Support\Facades\Log;

class Util
{
    /**
     * @param string $now made by function date('xx:xx:xx')
     * @return bool
     */
    public function isMidnight(string $now)
    {
        $nowTimestamp = strtotime($now);
        $startMidnight = strtotime(date('23:20:00'));
        $endMidnight = strtotime(date('08:00:00'));
        if ($nowTimestamp <= $startMidnight && $endMidnight <= $nowTimestamp) {
            // not midnight
            return false;
        }

        return true;
    }

    /**
     * @param string $minuteStr
     * @return bool
     * @throws \Exception
     */
    public function isEvenNumber(string $minuteStr)
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

        throw new \Exception('"分"以外の文字列が使用されています,使用されている文字「' . $minuteStr . '」');
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