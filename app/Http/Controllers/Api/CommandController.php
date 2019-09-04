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
        $textMessageBuilder = new TextMessageBuilder('JPCANADAバンクーバーお仕事配信botをご利用いただきありがとうございます。開発者Twitterをご覧の方はご存知と思いますが、LINEDevelopersアカウントの関係で不具合が起きており、botでのお仕事情報配信が困難な状態になったため、こちらのbotの運用開発を中止いたします。ご利用の方にはご迷惑をおかけし申し訳ありません。botを友達削除する場合は、botのメーッセージを削除した後botアカウントをブロックし、ブロックリストから削除してください。');
        $response = $bot->pushMessage($lineId, $textMessageBuilder);
    }
}