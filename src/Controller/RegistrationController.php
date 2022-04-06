<?php

namespace App\Controller;

use Exception;
use App\Entity\Users;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Interfaces\RegistrationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private RegistrationInterface $iRegistration;

    private TranslatorInterface $translator;

    private EmailVerifier $emailVerifier;

    public function __construct(
        RegistrationInterface $iRegistartion,
        TranslatorInterface $translator,
        EmailVerifier $emailVerifier
    ){
        $this->iRegistration = $iRegistartion;
        $this->translator = $translator;
        $this->emailVerifier = $emailVerifier;
        
    }

    /**
     * @Route("/register", name="app_register", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->iRegistration->register($form, $user);
            } catch (\Throwable $th) {
                throw new Exception("Il y a un problème avec le service des enrégistrements");
            }

            $this->addFlash('success', 'Bienvenue parmi nous. Un mail vient de vous être envoyé');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'title' => 'Inscription',
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     *
     * @param Request $request
     * @return Response
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $this->translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre adresse e-mail a été vérifiée.');

        return $this->redirectToRoute('app_home');
    }
}
