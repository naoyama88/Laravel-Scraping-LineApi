<?php

namespace App\Services\Job;

use App\Libs\Constant\JobCategory;
use SendGrid\Email;
use SendGrid\Content;
use SendGrid\Mail;

class RegisteredUserService
{
    public function getUserAddresses(string $sentType) : array
    {
        $registeredUserModel = new RegisteredUser($this->getPdo());
        $addresses = $registeredUserModel->getUserAddresses($sentType);

        $addresses =

        return $addresses;
    }
}
