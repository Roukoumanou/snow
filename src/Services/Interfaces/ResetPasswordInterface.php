<?php

namespace App\Services\Interfaces;

use App\Entity\Users;
use Symfony\Component\Form\Form;

interface ResetPasswordInterface
{
    public function reset(Users $user, Form $form, $token = null): bool;

    public function processSendingPasswordResetEmail(string $emailFormData): ?Users;
}
