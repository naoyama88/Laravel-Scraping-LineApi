<?php

namespace App\Services\Line;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class LineSendMessageService
{
    public function sendLineMessage($text)
    {
        $bot = app('line-bot');
        $lineId = env('LINE_ID_SAMPLE');
        $textMessageBuilder = new TextMessageBuilder($text);
        $response = $bot->pushMessage($lineId, $textMessageBuilder);
    }
}
