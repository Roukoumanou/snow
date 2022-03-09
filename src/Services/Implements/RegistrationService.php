<?php

namespace App\Services\Implements;

use App\Entity\Users;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Services\Interfaces\IMailer;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Interfaces\IRegistration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationService extends AbstractController implements IRegistration
{
    private $hasher;

    private $em;

    private $translator;

    private $emailVerifier;

    private $mailer;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        EmailVerifier $emailVerifier,
        IMailer $iMailer
    ) {
        $this->hasher = $userPasswordHasher;
        $this->em = $entityManager;
        $this->translator = $translator;
        $this->emailVerifier = $emailVerifier;
        $this->mailer = $iMailer;
    }

    /**
     * Service d'enrégistrement d'un utilisateur
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
            // encode the plain password
            $user->setPassword(
                $this->hasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->em->persist($user);
            $this->em->flush();

            $this->mailer->sendMail($user);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Service de vérification de l'email d'un utilisateur
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

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }
}
