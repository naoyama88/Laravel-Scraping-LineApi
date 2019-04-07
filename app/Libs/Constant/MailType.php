<?php

namespace App\Libs\Constant;

class MailType
{
    // SCHEDULE_01 depends on the scheduler. Every run time you can send message if there are new jobs.
    const TYPE_01 = 'sent_01';
    const TYPE_02 = 'sent_02';
    const TYPE_03 = 'sent_03';
    const MAIL_TYPE = [
        self::TYPE_01 => '1',
        self::TYPE_02 => '2',
        self::TYPE_03 => '3'
    ];
}
