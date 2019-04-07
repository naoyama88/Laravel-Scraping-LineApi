<?php

namespace App\Http\Controllers\Command;

use App\Libs\Util;
use Illuminate\Support\Facades\Log;

class SendJobInformationController
{
    public function send() : bool
    {
        // 実行時間の確認
        $util = new Util();
        if ($util->isMidnight(date('H:i:s'))) {
            Log::info('You cannot send emails this time. The time you can send is between 8:00 and 23:00');
            return true;
        }

        // 実行元がCLIかの確認、webからの実行でない場合は最後にupdateをしない
        $isRunFromCli = !empty($_SERVER['argv']);
        $sentType = $util->getSentType($isRunFromCli, $_SERVER);

        // メールに記載する仕事を取得
        $jobService = new JobService((new JpCanadaPdo())->getPdo());
        $todayJobs = $jobService->getTodayJob($sentType);
        if (empty($todayJobs)) {
            HerokuLog::debugLog('no job has registered today');
            return true;
        }

        // 取得した仕事からメール本文を作成
        $sendMailService = new SendMailService();
        $contentText = $sendMailService->makeContentText($todayJobs);

        // 送信するアドレス一覧を取得
        $registeredUserService = new RegisteredUserService((new JpCanadaPdo())->getPdo());
        if ($isRunFromCli === true) {
            $emailBccs = $registeredUserService->getUserAddresses($sentType);
            if (empty($emailBccs)) {
                // no address has registered
                HerokuLog::debugLog('no address has registered');
                return true;
            }
            HerokuLog::debugLog('Bcc counts ' . count($emailBccs));
        } else {
            $emailBccs = [getenv("EMAIL_SAMPLE_01")];
        }

        // メールを送信
        $response = $sendMailService->sendMail($contentText, $emailBccs);

        // メール送信が正しく行われたかチェック
        if (!empty($response) && substr($response->_status_code, 0, 1) != '2') {
            // http://sendgrid.com/docs/API_Reference/Web_API_v3/Mail/errors.html
            $responseBody = json_decode($response->_body);
            HerokuLog::debugLog($responseBody->errors->message);

            return false;
        }

        // 仕事レコードのアップデート
        if ($isRunFromCli === true) {
            $result = $jobService->updateAfterSentMail(array_column($todayJobs, 'id'), $sentType);
            if ($result === false) {
                HerokuLog::debugLog('fail to update.');
                return false;
            }
        }

        return true;
    }
}