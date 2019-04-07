<?php

namespace Service;

use App\Libs\Constant\JobCategory;
use Model\Job;

class JobService
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * JobService constructor.
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return \PDO
     */
    public function getPdo() : \PDO
    {
        return $this->pdo;
    }

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
        $doc = \phpQuery::newDocument($html);
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

    public function getTodayJob(string $sentType) : array
    {
        $jobModel = new Job($this->getPdo());
        $todayJobs = $jobModel->getJobs(date('Y-m-d 23:00:00', strtotime("-1 day")), date('Y-m-d 23:00:00'), $sentType);

        return $todayJobs;
    }

    public function updateAfterSentMail($ids, $sentType)
    {
        $jobModel = new Job($this->getPdo());
        $result = $jobModel->updateAfterSentMail($ids, $sentType);

        return $result;
    }
}