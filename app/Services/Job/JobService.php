<?php

namespace App\Services\Job;

use App\Libs\Constant\JobCategory;
use App\Libs\Constant\MailType;
use App\Models\Job;
use phpQuery;
use Illuminate\Support\Facades\Log;

class JobService
{
    public function extractNewJobs(array $jobRecords, $latestId): array
    {
        $newJobRecords = [];
        if (empty($latestId)) {
            $newJobRecords = $jobRecords;
        } else {
            // sort by id
            array_multisort(array_column($jobRecords, 'id'), SORT_DESC, $jobRecords);
            foreach ($jobRecords as $jobRecord) {
                if ($jobRecord['id'] > $latestId) {
                    $newJobRecords[] = $jobRecord;
                } else {
                    // break because the jobRecords have been already sorted so you don't need to continue anymore
                    break;
                }
            }
        }

        return $newJobRecords;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function scrapeJobs(): array
    {
        $html = file_get_contents("http://bbs.jpcanada.com/listing.php?bbs=4&order=2");
        $doc = phpQuery::newDocument($html);
        $jobRecords = [];
        foreach ($doc["#bbs-table"]->find("div.divTableRow") as $tableRow) {
            $jobRecord = [];

            // お仕事カテゴリ (取得直後ex. /icon/bbs238.png or http://bbs.jpcanada.com/icon/bbs997.gif)
            $category = pq($tableRow)->find('img')->attr('src');
            if (!in_array($category, JobCategory::JOB_CATEGORIES)) {
                // exclude 広告 or お知らせ
                continue;
            }
            $jobRecord['category'] = $category;

            // お仕事ID (取得直後ex. No.89827)
            $jobRecord['id'] = trim(pq($tableRow)->find('nobr')->text(), "No.");

            // お仕事タイトル (取得直後ex. 日本食卸で、倉庫係りを募集しております！)
            $jobRecord['title'] = pq($tableRow)->find('div.col4>a')->text();

            // お仕事リンク (取得直後ex. topics.php?bbs=4&msgid=89890&order=2&cat=&&dummy=0)
            $href = pq($tableRow)->find('div.col4>a')->attr('href');
            $jobRecord['href'] = 'http://bbs.jpcanada.com/' . $href;

            // 投稿時間 (取得直後ex. 2019-02-27 14:32:09 Charisma cafe and dessert house/バンクーバー)
            $jobRecord['post_datetime'] = substr(trim(pq($tableRow)->find('div.col4>span.post-detail')->text()), 0, 19);

            $jobRecords[] = $jobRecord;
        }

        return $jobRecords;
    }

    public function getTodayJob(string $sentType)
    {
        $from = date('Y-m-d 23:00:00', strtotime("-1 day"));
        $to = date('Y-m-d 23:00:00');
        switch ($sentType) {
            case MailType::TYPE_01:
                $sentTypeColumn = MailType::TYPE_01;
                break;
            case MailType::TYPE_02:
                $sentTypeColumn = MailType::TYPE_02;
                break;
            case MailType::TYPE_03:
                $sentTypeColumn = MailType::TYPE_03;
                break;
            default:
                Log::info('送信タイプ異常');
                return [];
        }
        $todayJobs = Job::whereBetween('post_datetime', [$from, $to])
            ->where($sentTypeColumn, '0')
            ->orderByDesc('id')
            ->get();

        return $todayJobs;
    }

    public function updateAfterSentMail($ids, $sentType)
    {
        switch ($sentType) {
            case MailType::TYPE_01:
                $sentTypeColumn = MailType::TYPE_01;
                break;
            case MailType::TYPE_02:
                $sentTypeColumn = MailType::TYPE_02;
                break;
            case MailType::TYPE_03:
                $sentTypeColumn = MailType::TYPE_03;
                break;
            default:
                Log::info('送信タイプ異常');
                return [];
        }
        Job::whereIn('id', $ids)
            ->update([$sentTypeColumn => 1]);
    }

    /**
     * get latest if from job table
     *
     * @return mixed|null
     */
    public function getLatestId()
    {
        $job = Job::orderBy('id', 'desc')
            ->first();
        if (empty($job)) {
            return null;
        }

        return $job->id;
    }

    /**
     * insert new jobs to job table
     *
     * @param array $newJobRecords
     * @return bool
     */
    public function insertNewJobs(array $newJobRecords) : bool
    {
        foreach ($newJobRecords as $insertJobRecord) {
            $job = new Job();
            $job->id = $insertJobRecord['id'];
            $job->category = $insertJobRecord['category'];
            $job->title = $insertJobRecord['title'];
            $job->href = $insertJobRecord['href'];
            $job->post_datetime = $insertJobRecord['post_datetime'];
            $job->sent_01 = '0';
            $job->sent_02 = '0';
            $job->sent_03 = '0';

            $job->save();
        }

        return true;
    }
}
