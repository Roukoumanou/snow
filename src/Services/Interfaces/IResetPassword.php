<?php

namespace App\Services\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

interface IResetPassword
{
    public function request(Request $request): Response;

    public function checkEmail(): Response;

    public function reset(Request $request, string $token = null): Response;

    public function processSendingPasswordResetEmail(string $emailFormData): RedirectResponse;
}
