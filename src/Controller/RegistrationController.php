<?php

namespace App\Controller;

use App\Services\Interfaces\IRegistration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Interfaces\RegistrationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationController extends AbstractController
{
    private RegistrationInterface $iRegistration;

    public function __construct(RegistrationInterface $iRegistartion)
    {
        $this->iRegistration = $iRegistartion;
    }

    /**
     * @Route("/register", name="app_register", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        return $this->iRegistration->register($request);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     *
     * @param Request $request
     * @return Response
     */
    public function verifyUserEmail(Request $request): Response
    {
        return $this->iRegistration->verifyUserEmail($request);
    }
}
