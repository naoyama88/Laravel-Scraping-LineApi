<?php

namespace App\Services\Line;

use App\Models\LineFriend;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Illuminate\Support\Facades\Log;

class LineSendMessageService
{
    public function sendLineMessageTest($text)
    {
        $bot = app('line-bot');
        $lineId = env('LINE_ID_SAMPLE');
        $textMessageBuilder = new TextMessageBuilder($text);
        $response = $bot->pushMessage($lineId, $textMessageBuilder);
    }

    public function sendLineMessage($text)
    {
        $bot = app('line-bot');
//        $lineIds[] = env('LINE_ID_SAMPLE'); // for test
        $lineIds = LineFriend::get(['line_id'])->toArray();
        $textMessageBuilder = new TextMessageBuilder($text);
        Log::info($lineIds);
//        $response = $bot->multicast($lineIds, $textMessageBuilder);
    }
}
