<?php

namespace Controller;

use App\Libs\Constant\JobCategory;
use Service\JobService;
use Model\JpCanadaPdo;
use Model\Job;
use App\Libs\Util;
use Illuminate\Support\Facades\Log;

class RegisterJobController
{
    public function registerJobs(): bool
    {
        $util = new Util();
        if ($util->isMidnight(date('H:i:s'))) {
            Log::info('Now it\'s midnight.');
            return true;
        }
        if ($util->isEvenNumber(date('i'))) {
            // If it's time which minute tens place number could be 0 or divisible by 2
            return true;
        }

        Log::info('start scrape');
        $jobService = new JobService((new JpCanadaPdo())->getPdo());
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

        $pdo = (new JpCanadaPdo())->getPdo();
        $jobModel = new Job($pdo);
        $latestId = $jobModel->selectLatestId();
        $newJobRecords = $jobService->extractNewJobs($listedJobs, $latestId);

        if (empty($newJobRecords)) {
            // no new jobs
            Log::info('no new jobs');
            return true;
        }

        $result = $jobModel->insertNewJobs($newJobRecords);
        Log::info('inserted jobs');

        if ($result === false) {
            Log::info('fail insert');
        }
        return $result;

    }
}