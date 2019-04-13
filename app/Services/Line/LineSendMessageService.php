<?php

namespace App\Services\Line;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class LineSendMessageService
{
    public function sendLineMessage($text)
    {
        $bot = app('line-bot');
        $lineId = 'U77aca8442a34fea506dfc9990738d242';
        $textMessageBuilder = new TextMessageBuilder($text);
        $response = $bot->pushMessage($lineId, $textMessageBuilder);
    }
}
