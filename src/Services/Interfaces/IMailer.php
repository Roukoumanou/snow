<?php

namespace App\Services\Interfaces;

interface IMailer
{
    public function sendMail(): void;
}