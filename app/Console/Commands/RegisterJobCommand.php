<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Libs\Util;
use App\Services\Job\JobService;
use App\Libs\Constant\JobCategory;

class RegisterJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:registerjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register job';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // 1時間に3回、1日に約35〜46回(herokuスケジューラのミスに依存)jpcanadaにアクセスし情報を取得
    public function handle()
    {
        $util = new Util();
        if ($util->isMidnight(date('H:i:s'))) {
            Log::info('Now it\'s midnight.');
            return true;
        }

        // the scheduler is expected to be set every 10 minutes
        // don't get job info if the tens place of the minutes is even number (not to access many times)
        $minutes = date('i');
        if ($util->isEvenNumber($minutes)) {
            Log::info('Now it\'s not time to scrape because the minutes is' . $minutes . '.');
            return true;
        }

        $jobService = new JobService();
        $listedJobs = $jobService->scrapeJobs();
        if (empty($listedJobs)) {
            Log::info('no job or could not get job');
            return false;
        }

        // just in case if something in the site I scrape has been changed
        $unknownCategory = array_diff(array_column($listedJobs, 'category'), array_keys(JobCategory::CATEGORIES));
        if (!empty($unknownCategory)) {
            Log::info("Unknown category has been found. Any other categories might have been added possibly.");
            return false;
        }

        $latestId = $jobService->getLatestId();
        $newJobRecords = $jobService->extractNewJobs($listedJobs, $latestId);

        if (empty($newJobRecords)) {
            // no new jobs
            Log::info('no new jobs');
            return true;
        }

        $result = $jobService->insertNewJobs($newJobRecords);

        return $result;

    }
}
