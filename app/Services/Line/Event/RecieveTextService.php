<?php

namespace App\Services\Line\Event;

use App\Services\Job\JobService;
use App\Services\Job\SendMailService;
use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\TextMessage;

class ReceiveTextService
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
     * 登録
     * @param TextMessage $event
     * @return string
     */
    public function execute(TextMessage $event)
    {
        $userText = $event->getText();
        $jobService = new JobService();
        // get job information related with the word user sent
        $jobs = $jobService->getJobsByText($userText);
        if (empty($jobs) || count($jobs) === 0) {
            return '"' . $userText . '"がタイトルに含まれるお仕事情報はありませんでした。（過去1ヶ月分対象）';
        }

        $sendMailService = new SendMailService();
        $lineText = $sendMailService->makeLineContentText($jobs);
        $lineText = '"' . $userText . '"がタイトルに含まれるお仕事情報が' . count($jobs) . '件見つかりました。（過去1ヶ月分対象）' . PHP_EOL . $lineText;

        return $lineText;
    }

}