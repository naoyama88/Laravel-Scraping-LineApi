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
        $lineIds = [];
        $lineFriends = LineFriend::get(['line_id']);
        foreach ($lineFriends as $lineFriend) {
            $lineIds[] = $lineFriend->line_id;
        }
        $textMessageBuilder = new TextMessageBuilder($text);
        Log::info('ライン送信確認ログ');
        Log::info('ライン送信確認ログ：送信先');
        Log::info($lineIds);
        Log::info('ライン送信確認ログ：送信内容(コンバート前)');
        Log::info($text);
        $response = $bot->multicast($lineIds, $textMessageBuilder);
        print_r($response);
    }
}
