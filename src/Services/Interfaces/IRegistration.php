<?php

namespace App\Services\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface IRegistration
{
    public function register(Request $request):Response;

    public function verifyUserEmail(Request $request): Response;
}