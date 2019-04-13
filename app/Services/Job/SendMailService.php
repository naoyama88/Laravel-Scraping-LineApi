<?php

namespace App\Services\Job;

use App\Libs\Constant\JobCategory;
use SendGrid\Email;
use SendGrid\Content;
use SendGrid\Mail;

class SendMailService
{
    /**
     * @param $todayJobs [Job]
     * @return string
     */
    public function makeContentText($todayJobs) : string
    {
        $newLine = '<br>';
        $contentText = '<h3>JPCANADA ' . $newLine . '仕事・求人＠バンクーバー</h3>';
        $today = date('Y年n月j日');
        $contentText .= '<h3>本日' . $today . 'に追加されたお仕事です。</h3>';
        $contentText .= $newLine;

        $tempIndex = 1;
        foreach ($todayJobs as $job) {
            // タイトル
            $contentText .= '<strong>' . $tempIndex . ') ' . '</strong><b>' . $job->title . '</b>';
            $contentText .= $newLine;

            // お仕事カテゴリー
            $contentText .= 'カテゴリ： ';
            $contentText .= JobCategory::CATEGORIES[$job->category];
            $contentText .= $newLine;

            // リンク
            $contentText .= $job->href;
            $contentText .= $newLine;

            // 入稿時間
            $contentText .= ' 入稿時間 ' . $job->post_datetime;
            $contentText .= $newLine;
            $contentText .= $newLine;

            $tempIndex++;
        }

        return $contentText;
    }

    public function sendMail($contentText, $emailBccs)
    {
        $fromName = "JPCANADA新着お仕事";
        $emailFrom = "noreply@jpcanada-scraper.com";
        $subject = "JPCANADA_本日追加されたお仕事";

        $from = new Email($fromName, $emailFrom);
        $tos = new Email(null, 'info@jpcanadascraper.com');
        $content = new Content("text/html", $contentText);
        $mail = new Mail($from, $subject, $tos, $content);
        foreach ($emailBccs as $bcc) {
            $mail->getPersonalizations()[0]->addBcc(new Email(null, $bcc));
        }

        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);

        return $response;
    }

    public function sendAlertToDeveloper(string $mailAddress, string $functionName)
    {
        $contextText =  '<h4>問題が発生しています。確認してください。</h4>';
        $contextText .=  $functionName;

        $fromName = "開発者宛_JPCANADA新着お仕事";
        $emailFrom = "noreply@jpcanada-scraper.com";
        $subject = "開発者宛_JPCANADA_本日追加されたお仕事";

        $from = new Email($fromName, $emailFrom);
        $tos = new Email(null, $mailAddress);
        $content = new Content("text/html", $contextText);
        $mail = new Mail($from, $subject, $tos, $content);

        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);

        return $response;
    }

    public function makeLineContentText($todayJobs) : string
    {
        $newLine = PHP_EOL;
        $contentText = 'JPCANADA ' . $newLine . '仕事・求人＠バンクーバー' . PHP_EOL;
        $today = date('Y年n月j日');
        $contentText .= '本日' . $today . 'に追加されたお仕事です。' . PHP_EOL;
        $contentText .= $newLine;

        $tempIndex = 1;
        foreach ($todayJobs as $job) {
            // タイトル
            $contentText .= $tempIndex . ') ' . $job->title;
            $contentText .= $newLine;

            // お仕事カテゴリー
            $contentText .= 'カテゴリ： ';
            $contentText .= JobCategory::CATEGORIES[$job->category];
            $contentText .= $newLine;

            // リンク
            $contentText .= $job->href;
            $contentText .= $newLine;

            // 入稿時間
            $contentText .= ' 入稿時間 ' . $job->post_datetime;
            $contentText .= $newLine;
            $contentText .= $newLine;

            $tempIndex++;
        }

        return $contentText;
    }
}
