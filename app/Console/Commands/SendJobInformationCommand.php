<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Libs\Util;
use App\Services\Job\JobService;
use App\Services\Job\SendMailService;

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
     * @return mixed
     */
    public function handle()
    {
        // 実行時間の確認
        $util = new Util();
        if ($util->isMidnight(date('H:i:s'))) {
            Log::info('Now it\'s midnight.');
            return true;
        }

        // 実行元がCLIかの確認、webからの実行の場合最後にupdateをしない
        $isRunFromCli = !empty($_SERVER['argv']);
        $sentType = $util->getSentType($isRunFromCli, $_SERVER);

        // メールに記載する仕事を取得
        $jobService = new JobService();
        $todayJobs = $jobService->getTodayJob($sentType);
        if (empty($todayJobs)) {
            Log::info('no job has registered today');
            return true;
        }

        // 取得した仕事からメール本文を作成
        $sendMailService = new SendMailService();
        $contentText = $sendMailService->makeContentText($todayJobs);

        // 送信するアドレス一覧を取得
//        $registeredUserService = new RegisteredUserService((new JpCanadaPdo())->getPdo()); test
        $isRunFromCli = false; // test
        if ($isRunFromCli === true) {
//            $emailBccs = $registeredUserService->getUserAddresses($sentType); test
            if (empty($emailBccs)) {
                // no address has registered
                Log::info('no address has registered');
                return true;
            }
            Log::info('Bcc counts ' . count($emailBccs));
        } else {
            $emailBccs = [getenv("EMAIL_SAMPLE_01")];
        }

        // メールを送信
        $response = $sendMailService->sendMail($contentText, $emailBccs);

        // メール送信が正しく行われたかチェック
        if (!empty($response) && substr($response->_status_code, 0, 1) != '2') {
            // http://sendgrid.com/docs/API_Reference/Web_API_v3/Mail/errors.html
            $responseBody = json_decode($response->_body);
            Log::info($responseBody->errors->message);

            return false;
        }

        // 仕事レコードのアップデート
        if ($isRunFromCli === true) {
            $result = $jobService->updateAfterSentMail(array_column($todayJobs, 'id'), $sentType);
            if ($result === false) {
                Log::info('fail to update.');
                return false;
            }
        }

        return true;
    }
}
