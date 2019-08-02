<?php

namespace App\Http\Controllers\Api;

use App\Libs\Constant\Messages;
use App\Services\Line\Event\ReceiveLocationService;
use App\Services\Line\Event\ReceiveTextService;
use App\Services\Line\Event\FollowService;
use App\Services\Line\Event\UnfollowService;
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
     *
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
            $replyMessage = Messages::NOT_SUPPORTED_OPERATE; // as default message

            switch (true){
                case $event instanceof FollowEvent:
                    // add friends or unblock
                    $service = new FollowService($bot);
                    $replyMessage = $service->execute($event)
                        ? Messages::ADD_FRIEND
                        : Messages::ADD_FRIEND_ERROR;

                    break;

                case $event instanceof TextMessage:
                    // receive message
                    $service = new ReceiveTextService($bot);
                    $replyMessage = $service->execute($event);
                    break;

                case $event instanceof LocationMessage:
                    // receive location
                    $service = new ReceiveLocationService($bot);
                    $replyMessage = $service->execute($event);
                    break;

                case $event instanceof PostbackEvent:
                    // select options
                    // TODO Do something
                    break;
                case $event instanceof UnfollowEvent:
                    // block
                    $service = new UnfollowService($bot);
                    $service->execute($event);
                    break;
                default:
                    $body = $event->getEventSourceId();
                    Log::warning('Unknown event. ['. get_class($event) . ']', compact('body'));
            }

            $bot->replyText($replyToken, $replyMessage);
        }
    }
}