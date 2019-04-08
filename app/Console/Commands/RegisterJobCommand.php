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

    public function handle()
    {
        Log::info('Success running script.');
        $util = new Util();
        if ($util->isMidnight(date('H:i:s'))) {
            Log::info('Now it\'s midnight.');
            return true;
        }

        // 0または偶数の場合は仕事情報を取得しない（過負荷防止のため）
        if ($util->isEvenNumber(date('i'))) {
            // If it's time which minute tens place number could be 0 or divisible by 2
            return true;
        }

        Log::info('start scrape');
        $jobService = new JobService();
        $listedJobs = $jobService->scrapeJobs();
        if (empty($listedJobs)) {
            Log::info('no job or could not get job');
            return false;
        }

        $unknownCategory = array_diff(array_column($listedJobs, 'category'), array_keys(JobCategory::CATEGORIES));
        if (!empty($unknownCategory)) {
            Log::info("Unknown category has been found. Any other categories might have been added possibly.");
            return false;
        }

        Log::info('just checked unknown category');

        $latestId = $jobService->getLatestId();
        $newJobRecords = $jobService->extractNewJobs($listedJobs, $latestId);

        if (empty($newJobRecords)) {
            // no new jobs
            Log::info('no new jobs');
            return true;
        }

        echo '<pre>';
        print_r($newJobRecords);
        echo '</pre>';
        Log::info('new jobs exist');

        $result = $jobService->insertNewJobs($newJobRecords);
        Log::info('inserted jobs');

        return $result;

    }
}
