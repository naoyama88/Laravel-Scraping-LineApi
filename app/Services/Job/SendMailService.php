<?php

namespace App\Services\Job;

use App\Libs\Constant\JobCategory;
use App\Libs\Constant\Messages;
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
        $contentText = Messages::MAIL_HEADER_1;
        $today = date(Messages::MAIL_CONTEXT_DATE_FORMAT);
        $contentText .= sprintf(Messages::MAIL_HEADER_2, $today);

        $newLine = '<br>';
        $tempIndex = 1;
        foreach ($todayJobs as $job) {
            // title
            $contentText .= '<strong>' . $tempIndex . ') ' . '</strong><b>' . $job->title . '</b>';
            $contentText .= $newLine;

            // job category
            $contentText .= 'カテゴリ： ';
            $contentText .= JobCategory::CATEGORIES[$job->category];
            $contentText .= $newLine;

            // hyper link
            $contentText .= $job->href;
            $contentText .= $newLine;

            // post datetime
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
            $mail->getPersonalizations()[0]->addBcc(new Email(null, $bcc->email));
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

    // LINEメッセージではJPCANADAアカウントから届くので、
    // メールでのメッセージにあるような「...追加されたお仕事です」などはしない
    public function makeLineContentText($todayJobs) : string
    {
        $newLine = PHP_EOL;
        $contentText = '';

        $tempIndex = 1;
        foreach ($todayJobs as $job) {
            // title
            $contentText .= $tempIndex . ') ' . $job->title;
            $contentText .= $newLine;

            // job category
            $contentText .= 'カテゴリ： ';
            $contentText .= JobCategory::CATEGORIES[$job->category];
            $contentText .= $newLine;

            // hyper link
            $contentText .= $job->href;
            $contentText .= $newLine;

            // post datetime
            $contentText .= ' 入稿時間 ' . $job->post_datetime;
            $contentText .= $newLine;
            $contentText .= $newLine;

            $tempIndex++;
        }

        $contentText = rtrim($contentText, PHP_EOL);

        return $contentText;
    }
}
