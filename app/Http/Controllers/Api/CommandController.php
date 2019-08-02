<?php

namespace App\Http\Controllers\Api;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class CommandController
{
    /**
     * push test
     */
    public function test()
    {
        $bot = app('line-bot');
        $lineId = 'U77aca8442a34fea506dfc9990738d242';
        $textMessageBuilder = new TextMessageBuilder('Test message.');
        $response = $bot->pushMessage($lineId, $textMessageBuilder);
    }
}