<?php

namespace App\Services\Interfaces;

use App\Entity\Users;
use Symfony\Component\Form\Form;

interface RegistrationInterface
{
    public function register(Form $form, Users $user): bool;
}
