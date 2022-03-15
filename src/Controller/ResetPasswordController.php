<?php

namespace App\Controller;

use App\Services\Interfaces\ResetPasswordInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reset-password")
 */
class ResetPasswordController extends AbstractController
{
    private ResetPasswordInterface $iResetPassword;

    public function __construct(ResetPasswordInterface $iResetPassword)
    {
        $this->iResetPassword = $iResetPassword;
    }

    /**
     * Display & process form to request a password reset.
     *
     * @Route("", name="app_forgot_password_request")
     */
    public function request(Request $request): Response
    {
        return $this->iResetPassword->request($request);
    }

    /**
     * Confirmation page after a user has requested a password reset.
     *
     * @Route("/check-email", name="app_check_email")
     */
    public function checkEmail(): Response
    {
        return $this->iResetPassword->checkEmail();
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     *
     * @Route("/reset/{token}", name="app_reset_password")
     */
    public function reset(Request $request, string $token = null): Response
    {
        return $this->iResetPassword->reset($request, $token);
    }
}
