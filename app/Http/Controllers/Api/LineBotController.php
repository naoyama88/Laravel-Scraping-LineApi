<?php

namespace App\Http\Controllers\Api;

use App\Services\Line\Event\RecieveLocationService;
use App\Services\Line\Event\ReceiveTextService;
use App\Services\Line\Event\FollowService;
use Illuminate\Http\Request;
use LINE\LINEBot;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\UnfollowEvent;
use LINE\LINEBot\Event\PostbackEvent;
use LINE\LINEBot\Event\MessageEvent\LocationMessage;
use LINE\LINEBot\Event\MessageEvent\TextMessage;

class LineBotController
{
    /**
     * callback from LINE Message API(webhook)
     * @param Request $request
     * @throws LINEBot\Exception\InvalidSignatureException
     * @throws \Exception
     */
    public function callback(Request $request)
    {
        /** @var LINEBot $bot */
        $bot = app('line-bot');

        $signature = $_SERVER['HTTP_'.LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
        if (!LINEBot\SignatureValidator::validateSignature($request->getContent(), env('LINE_CHANNEL_SECRET'), $signature)) {
            Log::info('received from difference line-server');
            abort(400);
        }

        $events = $bot->parseEventRequest($request->getContent(), $signature);

        /** @var LINEBot\Event\BaseEvent $event */
        foreach ($events as $event) {
            $replyToken = $event->getReplyToken();
            $replyMessage = 'その操作はサポートしてません。.[' . get_class($event) . '][' . $event->getType() . ']';

            switch (true){
                //友達登録＆ブロック解除
                case $event instanceof FollowEvent:
                    $service = new FollowService($bot);
                    $message = '友達登録ありがとうございます！JPCANADA(Vancouver)のお仕事情報をお伝えするアカウントです。' . PHP_EOL . PHP_EOL
                        . 'このアカウントは、JPCANADAのバンクーバー仕事・求人掲示板に新規投稿された投稿を順次お知らせします。' . PHP_EOL
                        . '既存の投稿にコメントがついた場合でもこちらのアカウントには流れてくることはありませんのでご了承ください。' . PHP_EOL
                        . 'お仕事が追加され次第順次お知らせするため通知が多くなる場合があります。煩わしいと感じる場合には当アカウントをミュートに設定してご利用ください。' . PHP_EOL
                        . 'また特定の文字をメッセージすると、その文字をタイトルに含むお仕事情報を過去1ヶ月のお仕事情報から取得し提供します。' . PHP_EOL . PHP_EOL
                        . '注意' . PHP_EOL
                        . '当アカウントは非公式ですので、JPCANADAが公開する一切の情報に関して関与しておりませんのでご注意ください。' . PHP_EOL
                        . '当アカウントを利用したことによってユーザーが負った不利益について、当アカウントは一切の責任を負いません。' . PHP_EOL
                        . '当サービスの管理者は金銭的収入を得ることはありません。またごく稀にお仕事情報以外の情報をアナウンスする場合がございます。ご了承ください。' . PHP_EOL
                        . 'また、当サービスは予告なく終了する場合がございます。ご注意ください。';
                    $replyMessage = $service->execute($event)
                        ? $message
                        : '友達登録ありがとうございます！';

                    break;
                //メッセージの受信
                case $event instanceof TextMessage:
                    $service = new ReceiveTextService($bot);
                    $replyMessage = $service->execute($event);
                    break;

                //位置情報の受信
                case $event instanceof LocationMessage:
                    $service = new RecieveLocationService($bot);
                    $replyMessage = $service->execute($event);
                    break;

                //選択肢とか選んだ時に受信するイベント
                case $event instanceof PostbackEvent:
                    break;
                //ブロック
                case $event instanceof UnfollowEvent:
                    break;
                default:
                    // 例:
                    $body = $event->getEventSourceId();
                    Log::warning('Unknown event. ['. get_class($event) . ']', compact('body'));
            }

            $bot->replyText($replyToken, $replyMessage);
        }
    }
}