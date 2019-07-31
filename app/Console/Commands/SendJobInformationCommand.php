<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Libs\Util;
use App\Services\Job\JobService;
use App\Services\Job\SendMailService;
use App\Services\Line\LineSendMessageService;
use App\Services\Job\RegisteredUserService;

class SendJobInformationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendjobinformation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send job information to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * TODO consider how to handle sent_type
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('start send_job_information');
        // 実行時間の確認
//        $util = new Util();
//        if ($util->isMidnight(date('H:i:s'))) {
//            Log::info('Now it\'s midnight.');
//            return true;
//        }

//        $sentType = $util->getSentType($isRunFromCli, $_SERVER);

        // メールに記載する仕事を取得
        $jobService = new JobService();
        $todayJobs = $jobService->getTodayJob('sent_01');
        if (empty($todayJobs) || count($todayJobs) === 0) {
            Log::info('no job has registered today');
            return true;
        }
        Log::info('jobs exist');

        // 取得した仕事からメール本文を作成
        $sendMailService = new SendMailService();
        $contentText = $sendMailService->makeContentText($todayJobs);
        $lineService = new LineSendMessageService();
        $lineText = $sendMailService->makeLineContentText($todayJobs);

        // 送信するアドレス一覧を取得
        $registeredUserService = new RegisteredUserService();
        $emailBccs = $registeredUserService->getUserAddresses('sent_01');
        if (empty($emailBccs)) {
            // no address has registered
            Log::info('no address has registered');
            return true;
        }
        Log::info('Bcc counts ' . count($emailBccs));
        Log::info('start sending mail');

        // メールを送信
        $response = $sendMailService->sendMail($contentText, $emailBccs);
        // ラインを送信
        $lineService->sendLineMessage($lineText);

        // メール送信が正しく行われたかチェック
//        if (!empty($response) && substr($response->_status_code, 0, 1) != '2') {
//            // http://sendgrid.com/docs/API_Reference/Web_API_v3/Mail/errors.html
//            $responseBody = json_decode($response->_body);
//            Log::info($responseBody->errors->message);
//
//            return false;
//        }
//
//        Log::info('finish sending mail');
//
//        // 仕事レコードのアップデート
//        $result = $jobService->updateAfterSentMail($todayJobs->pluck('id'), 'sent_01');
//        if ($result === false) {
//            Log::info('fail to update.');
//            return false;
//        }
//
//        Log::info('success to send');

        return true;
    }
}
