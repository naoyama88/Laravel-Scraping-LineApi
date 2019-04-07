<?php

namespace App\Libs\Constant;

class JobCategory
{
    const CATEGORY_OYAKUDACHI     = 'http://bbs.jpcanada.com/icon/bbs997.gif';
    const CATEGORY_OSHIRASE       = '/icon/bbs999.gif';
    const JOB_CATEGORY_RESTAURANT = '/icon/bbs238.png';
    const JOB_CATEGORY_CAFE       = '/icon/bbs239.png';
    const JOB_CATEGORY_PLAIN      = '/icon/bbs240.png';
    const JOB_CATEGORY_SCHOOL     = '/icon/bbs241.png';
    const JOB_CATEGORY_NATURE     = '/icon/bbs242.png';
    const JOB_CATEGORY_HEALTH     = '/icon/bbs243.png';
    const JOB_CATEGORY_MEDIA      = '/icon/bbs244.png';
    const JOB_CATEGORY_CAR        = '/icon/bbs245.png';
    const JOB_CATEGORY_PRO        = '/icon/bbs246.png';
    const JOB_CATEGORY_TRADE      = '/icon/bbs247.png';
    const JOB_CATEGORY_NPO        = '/icon/bbs248.png';
    const JOB_CATEGORY_IT         = '/icon/bbs249.png';
    const JOB_CATEGORY_EVENT      = '/icon/bbs250.png';
    const JOB_CATEGORY_BABY       = '/icon/bbs251.png';
    const JOB_CATEGORY_HANDY      = '/icon/bbs252.png';
    const JOB_CATEGORY_HAIR       = '/icon/bbs253.png';
    const JOB_CATEGORY_FASHION    = '/icon/bbs254.png';
    const JOB_CATEGORY_SELLER     = '/icon/bbs256.png';
    const JOB_CATEGORY_OTHERS     = '/icon/bbs257.png';

    // job category + other category
    const CATEGORIES = [
        self::CATEGORY_OYAKUDACHI     => '広告',
        self::CATEGORY_OSHIRASE       => 'お知らせ',
        self::JOB_CATEGORY_RESTAURANT => '飲食店',
        self::JOB_CATEGORY_CAFE       => 'カフェ・バー',
        self::JOB_CATEGORY_PLAIN      => '旅行・ツーリズム',
        self::JOB_CATEGORY_SCHOOL     => '学校・留学',
        self::JOB_CATEGORY_NATURE     => '造園',
        self::JOB_CATEGORY_HEALTH     => '医療・介護',
        self::JOB_CATEGORY_MEDIA      => 'メディア・出版',
        self::JOB_CATEGORY_CAR        => '自動車・オート',
        self::JOB_CATEGORY_PRO        => '専門職・コンサルタント・営業',
        self::JOB_CATEGORY_TRADE      => '貿易・運輸・商社',
        self::JOB_CATEGORY_NPO        => 'NPO・公益法人',
        self::JOB_CATEGORY_IT         => 'IT・通信',
        self::JOB_CATEGORY_EVENT      => 'イベント・パーティ',
        self::JOB_CATEGORY_BABY       => 'ナニー・ベビーシッター',
        self::JOB_CATEGORY_HANDY      => 'ハンディマン・作業系',
        self::JOB_CATEGORY_HAIR       => '理容・美容',
        self::JOB_CATEGORY_FASHION    => 'ファッション',
        self::JOB_CATEGORY_SELLER     => '販売・サービス',
        self::JOB_CATEGORY_OTHERS     => 'その他',
    ];

    // only job category
    const JOB_CATEGORIES = [
        self::JOB_CATEGORY_RESTAURANT,
        self::JOB_CATEGORY_CAFE,
        self::JOB_CATEGORY_PLAIN,
        self::JOB_CATEGORY_SCHOOL,
        self::JOB_CATEGORY_NATURE,
        self::JOB_CATEGORY_HEALTH,
        self::JOB_CATEGORY_MEDIA,
        self::JOB_CATEGORY_CAR,
        self::JOB_CATEGORY_PRO,
        self::JOB_CATEGORY_TRADE,
        self::JOB_CATEGORY_NPO,
        self::JOB_CATEGORY_IT,
        self::JOB_CATEGORY_EVENT,
        self::JOB_CATEGORY_BABY,
        self::JOB_CATEGORY_HANDY,
        self::JOB_CATEGORY_HAIR,
        self::JOB_CATEGORY_FASHION,
        self::JOB_CATEGORY_SELLER,
        self::JOB_CATEGORY_OTHERS
    ];
}