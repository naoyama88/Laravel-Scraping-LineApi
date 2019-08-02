<?php

namespace App\Services\Line\Event;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\LocationMessage;

class ReceiveLocationService
{
    /**
     * @var LineBot
     */
    private $bot;

    /**
     * Follow constructor.
     * @param LineBot $bot
     */
    public function __construct(LineBot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * Location
     * Do nothing now
     *
     * @param LocationMessage $event
     * @return string
     */
    public function execute(LocationMessage $event)
    {
        $string = "あなたの現在地は：" . PHP_EOL .$event->getAddress() . 'です。';
        $string .= PHP_EOL . '現在地を利用するサービスは提供していません。';
        return $string;
    }

}