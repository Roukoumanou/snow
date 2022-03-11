<?php

namespace App\Services\Interfaces;

use App\Entity\Users;

interface IMailer
{
    public function sendMail(Users $user): void;

    public function sendMailForResetPassword(Users $user, $resetToken): void;
}
