<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Services\Interfaces\IRegistration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{

    /**
     * @Route("/register", name="app_register", methods={"GET","POST"})
     *
     * @param Request $request
     * @param IRegistration $iRegistration
     * @return Response
     */
    public function register(Request $request, IRegistration $iRegistration): Response
    {
        return $iRegistration->register($request);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     *
     * @param Request $request
     * @param IRegistration $iRegistration
     * @return Response
     */
    public function verifyUserEmail(Request $request, IRegistration $iRegistration): Response
    {
        return $iRegistration->verifyUserEmail($request);
    }
}
