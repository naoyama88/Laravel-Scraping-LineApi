<?php

namespace App\Services\Job;

use App\Libs\Constant\MailType;
use App\Models\RegisteredUser;

class RegisteredUserService
{
    public function getUserAddresses(string $sentType) : array
    {
        $addresses = RegisteredUser::where('email_cycle_status', MailType::MAIL_TYPE[$sentType])
            ->get();

        return $addresses;
    }
}
